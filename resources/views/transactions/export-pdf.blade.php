<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thống kê giao dịch</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .summary {
            margin-bottom: 30px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Thống kê giao dịch</h1>
        <p>Từ {{ $start_date->format('d/m/Y') }} đến {{ $end_date->format('d/m/Y') }}</p>
        <p>Người dùng: {{ $user->name }}</p>
    </div>

    <div class="summary">
        <h2>Tổng quan</h2>
        <div class="summary-item">
            <strong>Tổng giao dịch:</strong> {{ $summary['total_transactions'] }}
        </div>
        <div class="summary-item">
            <strong>Đã chia sẻ:</strong> {{ $summary['shared_transactions'] }}
        </div>
        <div class="summary-item">
            <strong>Đã nhận:</strong> {{ $summary['received_transactions'] }}
        </div>

    </div>

    @if($category_stats->count() > 0)
    <div>
        <h2>Thống kê theo danh mục</h2>
        <table>
            <thead>
                <tr>
                    <th>Danh mục</th>
                    <th class="text-center">Số lượng</th>
                </tr>
            </thead>
            <tbody>
                @foreach($category_stats as $category => $count)
                <tr>
                    <td>{{ $category }}</td>
                    <td class="text-center">{{ $count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div>
        <h2>Chi tiết giao dịch</h2>
        <table>
            <thead>
                <tr>
                    <th>Ngày</th>
                    <th>Loại</th>
                    <th>Món đồ</th>
                    <th>Danh mục</th>
                    <th>Số lượng</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                    <td>
                        @if($user->id == $transaction->giver_id)
                            Đã chia sẻ
                        @else
                            Đã nhận
                        @endif
                    </td>
                    <td>{{ $transaction->post->title ?? 'N/A' }}</td>
                    <td>{{ $transaction->post->category->name ?? 'N/A' }}</td>
                    <td class="text-center">{{ $transaction->quantity }}</td>
                    <td>
                        @switch($transaction->status)
                            @case('pending')
                                Đang chờ
                                @break
                            @case('accepted')
                                Đã chấp nhận
                                @break
                            @case('completed')
                                Đã hoàn thành
                                @break
                            @case('rejected')
                                Đã từ chối
                                @break
                            @default
                                Không xác định
                        @endswitch
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Không có giao dịch nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
