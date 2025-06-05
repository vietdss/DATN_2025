<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TransactionService;
use App\Exports\TransactionExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function statistics(Request $request)
    {
        $data = $this->service->statistics($request);
        if (empty($data['transactions']) && $request->has('start_date')) {
            session()->flash('info', 'Không có giao dịch nào trong khoảng thời gian đã chọn.');
        }
        return view('transactions.stat', $data);
    }

    public function exportExcel(Request $request)
    {
        $data = $this->service->getExportData($request);
        $filename = 'thong-ke-giao-dich-' . now()->format('Y-m-d-H-i-s') . '.xlsx';
        
        return Excel::download(new TransactionExport($data), $filename);
    }

    public function exportCsv(Request $request)
    {
        $data = $this->service->getExportData($request);
        $filename = 'thong-ke-giao-dich-' . now()->format('Y-m-d-H-i-s') . '.csv';
        
        return Excel::download(new TransactionExport($data), $filename, \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->service->getExportData($request);
        $pdf = Pdf::loadView('transactions.export-pdf', $data);
        $filename = 'thong-ke-giao-dich-' . now()->format('Y-m-d-H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }
}
