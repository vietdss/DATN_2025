<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data['transactions'];
    }

    public function headings(): array
    {
        return [
            'Ngày tạo',
            'Loại giao dịch',
            'Tên món đồ',
            'Danh mục',
            'Số lượng',
            'Người cho',
            'Người nhận',
            'Trạng thái',
            'Ngày cập nhật'
        ];
    }

    public function map($transaction): array
    {
        $user = auth()->user();
        $transactionType = $transaction->giver_id == $user->id ? 'Đã chia sẻ' : 'Đã nhận';
        
        return [
            $transaction->created_at->format('d/m/Y H:i'),
            $transactionType,
            $transaction->post->title ?? 'N/A',
            $transaction->post->category->name ?? 'N/A',
            $transaction->quantity,
            $transaction->giver->name ?? 'N/A',
            $transaction->receiver->name ?? 'N/A',
            $this->translateStatus($transaction->status),
            $transaction->updated_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Thống kê giao dịch';
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
}
