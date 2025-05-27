<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    protected $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $data = $this->service->getTransactions();
        
        // Mark requests as read when viewing transactions page
        $this->service->markRequestsAsRead();
        
        return view('transactions.index', $data);
    }

    public function store(Request $request, $id)
    {
        $result = $this->service->store($request, $id);
        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']], $result['code']);
        }
        return response()->json(['message' => $result['success']]);
    }

    public function update(Request $request, $id)
    {
        $result = $this->service->update($request, $id);
        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']], $result['code']);
        }
        return response()->json(['message' => $result['success']]);
    }

    public function destroy($id)
    {
        $result = $this->service->destroy($id);
        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']], $result['code']);
        }
        return response()->json(['message' => $result['success']]);
    }

    public function getUnreadCount()
    {
        $count = $this->service->getUnreadRequestsCount();
        return response()->json(['count' => $count]);
    }

    public function markAsRead()
    {
        $this->service->markRequestsAsRead();
        return response()->json(['success' => true]);
    }

    public function statistics(Request $request)
    {
        $data = $this->service->statistics($request);
        if (empty($data['transactions']) && $request->has('start_date')) {
            session()->flash('info', 'Không có giao dịch nào trong khoảng thời gian đã chọn.');
        }
        return view('transactions.stat', $data);
    }
}
