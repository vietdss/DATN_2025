<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{
    // --- Transaction CRUD & Stat ---
    public function index()
{
    $transactions = Transaction::with([
        'receiver',
        'post' => function ($query) {
            $query->withTrashed();
        }
    ])
        ->where('giver_id', Auth::id())
        ->latest()
        ->get();

    // Thêm danh sách đã gửi
    $sentTransactions = Transaction::with([
        'post' => function ($query) {
            $query->withTrashed();
        },
        'giver'
    ])
        ->where('receiver_id', Auth::id())
        ->latest()
        ->get();

    return view('transactions.index', compact('transactions', 'sentTransactions'));
}

public function store(Request $request, $id)
{
    $item = Item::findOrFail($id);
    if (!$item->is_approved) {
        return response()->json(['message' => 'Bài đăng này đang chờ duyệt, không thể gửi yêu cầu.'], 403);
    }
    if ($item->user_id == Auth::id()) {
        return response()->json(['message' => 'Bạn không thể yêu cầu chính bài đăng của mình.'], 403);
    }

    $existing = Transaction::where('post_id', $id)
        ->where('receiver_id', Auth::id())
        ->first();

    if ($existing) {
        return response()->json(['message' => 'Bạn đã gửi yêu cầu trước đó.'], 409);
    }

    $quantity = (int) $request->input('quantity', 1);

    // Tính tổng số lượng đã được yêu cầu (pending + accepted chưa completed)
    $requested = $item->transactions()
        ->whereIn('status', ['pending', 'accepted'])
        ->sum('quantity');

    $available = $item->quantity - $requested;

    if ($available < $quantity) {
        return response()->json(['message' => 'Không đủ số lượng khả dụng.'], 400);
    }

    Transaction::create([
        'post_id' => $item->id,
        'giver_id' => $item->user_id,
        'receiver_id' => Auth::id(),
        'status' => 'pending',
        'quantity' => $quantity,
    ]);

    $this->updateItemStatus($item);

    return response()->json(['message' => 'Yêu cầu đã được gửi thành công!']);
}

public function update(Request $request, $id)
{
    $item = Item::findOrFail($id);
    if (!$item->is_approved && Auth::id() !== $item->user_id) {
        abort(403, 'Bài đăng chưa được duyệt.');
    }
    $transaction = Transaction::where('id', $id)
        ->where('giver_id', auth()->id())
        ->firstOrFail();
 $item = $transaction->post;
    if (!$item->is_approved) {
        return response()->json(['message' => 'Bài đăng này đang chờ duyệt, không thể thao tác.'], 403);
    } 
    $action = $request->input('action');

    switch ($action) {
        case 'accept':
            // Kiểm tra lại số lượng khả dụng trước khi chấp nhận
            $item = $transaction->post;
            if ($item->quantity < $transaction->quantity) {
                return response()->json(['message' => 'Không đủ số lượng khả dụng để chấp nhận yêu cầu.'], 400);
            }
            $transaction->status = 'accepted';
            $item->decrement('quantity', $transaction->quantity);
            $this->updateItemStatus($item);
            break;
        case 'reject':
            $transaction->status = 'rejected';
            // Không cộng lại số lượng vì chưa từng trừ
            break;
        case 'pending':
            $transaction->status = 'pending';
            break;
        case 'completed':
            $transaction->status = 'completed';
            break;
    }

    $transaction->save();

    return response()->json(['message' => 'Cập nhật trạng thái thành công!']);
}

public function destroy($id)
{
    $transaction = Transaction::where('post_id', $id)
        ->where('receiver_id', Auth::id())
        ->first();

    if (!$transaction) {
        return response()->json(['message' => 'Yêu cầu không tồn tại.'], 404);
    }

    // Không cộng lại số lượng vì chỉ trừ khi accepted
    $transaction->delete();

    $this->updateItemStatus($transaction->post);

    return response()->json(['message' => 'Đã hủy yêu cầu thành công.']);
}

    // --- Statistics (moved from StatisticsController) ---
    public function statistics(Request $request)
    {
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : $endDate->copy()->subDays(30);

        $endDate = $endDate->endOfDay();
        $startDate = $startDate->startOfDay();

        $previousStartDate = $startDate->copy()->subDays($startDate->diffInDays($endDate));
        $previousEndDate = $startDate->copy()->subDay();

        $user = auth()->user();

        $totalTransactions = Transaction::where(function($query) use ($user) {
                $query->where('giver_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $previousTotalTransactions = Transaction::where(function($query) use ($user) {
                $query->where('giver_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->count();

        $sharedTransactions = Transaction::where('giver_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])->where('status', 'completed')
            ->count();

        $previousSharedTransactions = Transaction::where('giver_id', $user->id)
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->count();

        $receivedTransactions = Transaction::where('receiver_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $previousReceivedTransactions = Transaction::where('receiver_id', $user->id)
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->count();

        $averageRating = 4.8; // Giá trị mẫu
        $previousAverageRating = 4.6; // Giá trị mẫu

        $transactionGrowth = $previousTotalTransactions > 0
            ? round((($totalTransactions - $previousTotalTransactions) / $previousTotalTransactions) * 100)
            : 0;

        $sharedGrowth = $previousSharedTransactions > 0
            ? round((($sharedTransactions - $previousSharedTransactions) / $previousSharedTransactions) * 100)
            : 0;

        $receivedGrowth = $previousReceivedTransactions > 0
            ? round((($receivedTransactions - $previousReceivedTransactions) / $previousReceivedTransactions) * 100)
            : 0;

        $ratingGrowth = $previousAverageRating > 0
            ? round(($averageRating - $previousAverageRating), 1)
            : 0;

        $timeChartLabels = [];
        $sharedTimeData = [];
        $receivedTimeData = [];

        $daysDiff = $startDate->diffInDays($endDate);
        if ($daysDiff > 60) {
            $currentDate = $startDate->copy()->startOfMonth();
            while ($currentDate->lte($endDate)) {
                $timeChartLabels[] = $currentDate->format('m/Y');
                $monthEnd = $currentDate->copy()->endOfMonth();
                if ($monthEnd->gt($endDate)) {
                    $monthEnd = $endDate->copy();
                }
                $sharedCount = Transaction::where('giver_id', $user->id)
                    ->whereBetween('created_at', [$currentDate, $monthEnd])
                    ->count();
                $sharedTimeData[] = $sharedCount;

                $receivedCount = Transaction::where('receiver_id', $user->id)
                    ->whereBetween('created_at', [$currentDate, $monthEnd])
                    ->count();
                $receivedTimeData[] = $receivedCount;

                $currentDate->addMonth();
            }
        } else if ($daysDiff > 30) {
            $interval = 14;
            $this->generateTimeChartData($startDate, $endDate, $interval, $user, $timeChartLabels, $sharedTimeData, $receivedTimeData);
        } else if ($daysDiff > 14) {
            $interval = 7;
            $this->generateTimeChartData($startDate, $endDate, $interval, $user, $timeChartLabels, $sharedTimeData, $receivedTimeData);
        } else if ($daysDiff > 7) {
            $interval = 2;
            $this->generateTimeChartData($startDate, $endDate, $interval, $user, $timeChartLabels, $sharedTimeData, $receivedTimeData);
        } else {
            $interval = 1;
            $this->generateTimeChartData($startDate, $endDate, $interval, $user, $timeChartLabels, $sharedTimeData, $receivedTimeData);
        }

        $lastLabel = end($timeChartLabels);
        $today = Carbon::now()->format('d/m');

        if ($lastLabel != $today && $endDate->isToday()) {
            $timeChartLabels[] = $today;
            $todayStart = Carbon::today()->startOfDay();
            $todayEnd = Carbon::today()->endOfDay();

            $sharedCount = Transaction::where('giver_id', $user->id)
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->count();
            $sharedTimeData[] = $sharedCount;

            $receivedCount = Transaction::where('receiver_id', $user->id)
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->count();
            $receivedTimeData[] = $receivedCount;
        }

        $categoryData = DB::table('transactions')
            ->join('items', 'transactions.post_id', '=', 'items.id')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->where(function($query) use ($user) {
                $query->where('transactions.giver_id', $user->id)
                      ->orWhere('transactions.receiver_id', $user->id);
            })
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->select('categories.name', DB::raw('count(*) as total'))
            ->groupBy('categories.name')
            ->orderBy('total', 'desc')
            ->get();

        $categoryLabels = $categoryData->pluck('name')->toArray();
        $categoryData = $categoryData->pluck('total')->toArray();

        $transactions = Transaction::with([
            'post' => function ($query) {
                $query->withTrashed();
            },
            'post.category',
            'giver',
            'receiver'
        ])
            ->where(function($query) use ($user) {
                $query->where('giver_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate(5);

        if ($transactions->isEmpty() && $request->has('start_date')) {
            session()->flash('info', 'Không có giao dịch nào trong khoảng thời gian đã chọn.');
        }

        return view('transactions.stat', compact(
            'totalTransactions', 'sharedTransactions', 'receivedTransactions', 'averageRating',
            'transactionGrowth', 'sharedGrowth', 'receivedGrowth', 'ratingGrowth',
            'timeChartLabels', 'sharedTimeData', 'receivedTimeData',
            'categoryLabels', 'categoryData', 'transactions',
            'startDate', 'endDate'
        ));
    }

    private function generateTimeChartData($startDate, $endDate, $interval, $user, &$timeChartLabels, &$sharedTimeData, &$receivedTimeData)
    {
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $intervalEnd = $currentDate->copy()->addDays($interval);
            if ($intervalEnd->gt($endDate)) {
                $intervalEnd = $endDate->copy();
            }

            $timeChartLabels[] = $currentDate->format('d/m');

            $sharedCount = Transaction::where('giver_id', $user->id)
                ->whereBetween('created_at', [$currentDate, $intervalEnd])
                ->count();
            $sharedTimeData[] = $sharedCount;

            $receivedCount = Transaction::where('receiver_id', $user->id)
                ->whereBetween('created_at', [$currentDate, $intervalEnd])
                ->count();
            $receivedTimeData[] = $receivedCount;

            $currentDate->addDays($interval);
        }
    }

    


    private function translateStatus($status)
    {
        switch ($status) {
            case 'pending':
                return 'Đang chờ';
            case 'accepted':
                return 'Đã chấp nhận';
            case 'completed':
                return 'Đã hoàn thành';
            case 'rejected':
                return 'Đã từ chối';
            default:
                return 'Không xác định';
        }
    }
    public function updateItemStatus(Item $item)
{
    if ($item->quantity <= 0) {
        $item->status = 'Taken';
    } elseif ($item->transactions()->where('status', 'pending')->exists()) {
        $item->status = 'Reserved';
    } else {
        $item->status = 'Available';
    }

    $item->save();
}

}