<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class ItemService
{
    protected $cohereService;

    public function __construct(CohereService $cohereService)
    {
        $this->cohereService = $cohereService;
    }
    public function getAll()
{
    $query = Item::with('category')->where('is_approved', 1);
    
    // Kiểm tra xem người dùng có đăng nhập hay không
    if (auth()->check()) {
        $userId = auth()->id(); // Lấy ID của người dùng đăng nhập
        $query->where('user_id', '!=', $userId); // Không lấy item của người dùng hiện tại
    }

    return $query->get();
}

    public function getWithPaginate()
    {
        return Item::with('category')->paginate(6);
    }

    public function getById($id)
    {
        return Item::findOrFail($id);
    }
    
    public function getUserItem($id){
        return Item::where('user_id',$id)->paginate(4);
    }
    public function create($data)
    {
        return Item::create($data);
    }

   public function update($id, $data)
{
    $item = Item::findOrFail($id);

    if (isset($data['quantity'])) {
        // Tổng số lượng đã được yêu cầu (pending + accepted)
        $requested = $item->transactions()
            ->whereIn('status', ['pending', 'accepted'])
            ->sum('quantity');
        if ($data['quantity'] < $requested) {
            $link = route('transactions.index');
            throw new \Exception(
                'Không thể giảm số lượng nhỏ hơn tổng số lượng đã được yêu cầu (' . $requested . '). ' .
                'Vui lòng <a href="'.$link.'" style="color:#2563eb;text-decoration:underline;">xử lý các yêu cầu</a> trước khi chỉnh sửa.'
            );
        }
    }

    $data['is_approved'] = 0; 
    $item->update($data);

     $hasTransactions = $item->transactions()
        ->whereIn('status', ['pending', 'accepted'])
        ->exists();

    if ($hasTransactions) {
        app(\App\Http\Controllers\TransactionController::class)->updateItemStatus($item);
    }

    return $item;
}

    public function delete($id)
{
    $item = Item::findOrFail($id);

    // Cập nhật tất cả transaction liên quan thành 'rejected'
    $item->transactions() // nếu bạn có quan hệ transactions()
         ->whereIn('status', ['pending', 'accepted'])
         ->update(['status' => 'rejected']);

    // Soft delete item
    return $item->delete();
}
public function search($params)
    {
        $queryBuilder = Item::with(['category', 'images']); 
        $queryBuilder->where('is_approved', 1);

        if (Auth::check()) {
            $userId = Auth::id();
            $queryBuilder->where('user_id', '!=', $userId);
        }
        $queryBuilder->where('status', '!=', 'Taken');
        
        // Các điều kiện lọc KHÁC (category, distance)
        if (!empty($params['category_id'])) {
            $queryBuilder->where('category_id', $params['category_id']);
        }

        if (!empty($params['latitude']) && !empty($params['longitude']) && !empty($params['distance'])) {
            $latitude = $params['latitude'];
            $longitude = $params['longitude'];
            $distance = $params['distance'];

            $queryBuilder->selectRaw("*, 
                (6371 * ACOS(
                    COS(RADIANS(?)) * COS(RADIANS(JSON_UNQUOTE(JSON_EXTRACT(location, '$.lat')))) * 
                    COS(RADIANS(JSON_UNQUOTE(JSON_EXTRACT(location, '$.lng'))) - RADIANS(?)) + 
                    SIN(RADIANS(?)) * SIN(RADIANS(JSON_UNQUOTE(JSON_EXTRACT(location, '$.lat'))))
                )) AS distance", [
                    $latitude, $longitude, $latitude
                ])->having('distance', '<=', $distance);
        }
if (!empty($params['sort'])) {
    switch ($params['sort']) {
        case 'newest':
            $queryBuilder->orderBy('created_at', 'desc');
            break;
        case 'oldest':
            $queryBuilder->orderBy('created_at', 'asc');
            break;
        // Thêm các case khác nếu cần
    }
}
        // Lấy dữ liệu trước (có thể giới hạn để tối ưu)
        $items = $queryBuilder->get();

        if (!empty($params['search']) && $items->isNotEmpty()) {
            $documents = $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => collect($item->getAttributes())->map(fn($v, $k) => "$k: $v")->implode(', '),
                ];
            })->values()->toArray();

            try {
                $reranked = $this->cohereService->rerank($params['search'], $documents);
                $ids = array_column($reranked, 'id');

                // ✅ FIX: Load relationships when reordering
                $items = Item::with(['category', 'images'])
                    ->whereIn('id', $ids)
                    ->get()
                    ->sortBy(function ($item) use ($ids) {
                        return array_search($item->id, $ids);
                    })->values();
            } catch (\Exception $e) {
                \Log::error("Cohere error: " . $e->getMessage());
            }
        }

        return $items;
    }




    

}
