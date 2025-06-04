<?php

namespace App\Http\Controllers;

use App\Mail\NewItemSubmitted;
use App\Models\Transaction;
use App\Services\CategoryService;
use App\Services\ImageService;
use App\Services\ItemService;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ItemController extends Controller
{
    protected $itemService;
    protected $categoryService;
    protected $imageService;
    protected $recommendationService;

    public function __construct(
        ItemService $itemService, 
        CategoryService $categoryService, 
        ImageService $imageService,
        RecommendationService $recommendationService
    )
    {
        $this->itemService = $itemService;
        $this->categoryService = $categoryService;
        $this->imageService = $imageService;
        $this->recommendationService = $recommendationService;
    }

    public function index(Request $request)
{
    $params = $request->query();

    // Gọi search và nhận kết quả là Collection
    $items = $this->itemService->search($params); // Collection

    // Phân trang thủ công trên collection
    $perPage = 6;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $pagedItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->values();

    $itemsWithPaginate = new LengthAwarePaginator(
        $pagedItems,
        $items->count(),
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'query' => request()->query()]
    );

    // Lấy danh mục
    $categories = $this->categoryService->getAll();
;    return view('item.index', [
        'items' => $items,                       // tất cả (Collection đầy đủ)
        'itemsWithPaginate' => $itemsWithPaginate, // kết quả sau phân trang
        'categories' => $categories
    ]);
}

    public function createForm()
    {
        $categories = $this->categoryService->getAll();
        return view('item.create', ['categories' => $categories]);
    }
    
    public function store(Request $request)
    {
        $data = $request->all();
        $item = $this->itemService->create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Use the new uploadAndCreate method
                $this->imageService->uploadAndCreate($image, $item->id);
            }
        }
        Mail::to('nguyenhoangviet251103@gmail.com')->send(new NewItemSubmitted($item));

        return redirect()->route('item.detail', ['id' => $item->id])->with('success', 'Đăng tin thành công!');
    }

    public function detail($id)
    {
        $item = $this->itemService->getById($id);
        if (!$item->is_approved && $item->user_id !== Auth::id()) {
    abort(404);
}
        // Get similar items using KNN algorithm
        $similarItems = $this->recommendationService->getSimilarItems($id);
        $userTransaction = Transaction::where('post_id', $item->id)
        ->where('receiver_id', Auth::id())
        ->latest()
        ->first();
            
        return view('item.detail', [
            'item' => $item,
            'similarItems' => $similarItems,
            'userTransaction' => $userTransaction,

        ]);
    }
    
   public function edit($id, Request $request)
{
    $item = $this->itemService->getById($id);

    if (Auth::id() !== $item->user_id) {
        abort(403, 'Bạn không có quyền chỉnh sửa bài đăng này.');
    }

    // Kiểm tra nếu đã có transaction pending hoặc accepted
    $hasRequest = $item->transactions()
        ->whereIn('status', ['pending', 'accepted'])
        ->exists();

    if ($hasRequest) {
        // Có thể chuyển hướng về trang chi tiết và báo lỗi
        return redirect()->route('item.detail', $item->id)
            ->with('error', 'Không thể chỉnh sửa vì sản phẩm đã có yêu cầu nhận hoặc đang xử lý.');
    }

    $categories = $this->categoryService->getAll();

    return view('item.edit', [
        'item' => $item,
        'categories' => $categories
    ]);
}
    
    public function update($id, Request $request)
{
    try {
        $data = $request->all();
        $item = $this->itemService->update($id, $data);

        if ($request->filled('deleted_images')) {
            foreach ($request->input('deleted_images') as $imageId) {
                $this->imageService->delete($imageId);
            }
        }

        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $this->imageService->uploadAndCreate($image, $id);
            }
        }
        Mail::to('nguyenhoangviet251103@gmail.com')->send(new NewItemSubmitted($item));

        return redirect()->route('item.detail', ['id' => $id])->with('success', 'Cập nhật thành công!');
    } catch (\Exception $e) {
        // Hiển thị thông báo lỗi và giữ lại dữ liệu cũ
        return redirect()->back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}

    public function destroy($id)
    {
        // Get the item
        $item = $this->itemService->getById($id);
        
        // Delete all associated images (the ImageService will handle Cloudinary deletion)
        foreach ($item->images as $image) {
            $this->imageService->delete($image->id);
        }
        
        // Delete the item
        $this->itemService->delete($id);

        return redirect()->route('user.profile', ['id' => auth()->id()]);
    }
}