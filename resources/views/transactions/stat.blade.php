@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Thống kê giao dịch</h1>

        <!-- Date Range Filter -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-end space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex-1">
                    <label for="dateRange" class="block text-sm font-medium text-gray-700 mb-1">Khoảng thời gian</label>
                    <select id="dateRange"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                        <option value="7days" {{ request('date_range') == '7days' ? 'selected' : '' }}>7 ngày qua</option>
                        <option value="30days" {{ request('date_range', '30days') == '30days' ? 'selected' : '' }}>30 ngày qua
                        </option>
                        <option value="3months" {{ request('date_range') == '3months' ? 'selected' : '' }}>3 tháng qua
                        </option>
                        <option value="6months" {{ request('date_range') == '6months' ? 'selected' : '' }}>6 tháng qua
                        </option>
                        <option value="1year" {{ request('date_range') == '1year' ? 'selected' : '' }}>1 năm qua</option>
                        <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Tùy chỉnh</option>
                    </select>
                </div>

                <div class="flex-1 {{ request('date_range') == 'custom' ? '' : 'hidden' }}" id="customDateContainer">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="startDate" class="block text-sm font-medium text-gray-700 mb-1">Từ ngày</label>
                            <input type="date" id="startDate" name="start_date"
                                value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label for="endDate" class="block text-sm font-medium text-gray-700 mb-1">Đến ngày</label>
                            <input type="date" id="endDate" name="end_date"
                                value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>
                </div>

                <div>
                    <button id="applyDateFilter"
                        class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                        Áp dụng
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-3 gap-6 mb-8">
            <!-- Tổng giao dịch -->
            <div class="bg-white rounded-lg shadow-md p-6 transition duration-300 hover:shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">Tổng giao dịch</h2>
                    <span class="text-green-600 bg-green-100 rounded-full p-2">
                        <i class="fas fa-exchange-alt"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold">{{ $totalTransactions }}</p>
            </div>

            <!-- Đã chia sẻ -->
            <div class="bg-white rounded-lg shadow-md p-6 transition duration-300 hover:shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">Đã chia sẻ</h2>
                    <span class="text-blue-600 bg-blue-100 rounded-full p-2">
                        <i class="fas fa-share-alt"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold">{{ $sharedTransactions }}</p>
            </div>

            <!-- Đã nhận -->
            <div class="bg-white rounded-lg shadow-md p-6 transition duration-300 hover:shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">Đã nhận</h2>
                    <span class="text-purple-600 bg-purple-100 rounded-full p-2">
                        <i class="fas fa-hand-holding"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold">{{ $receivedTransactions }}</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Transaction Over Time Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Giao dịch theo thời gian</h2>
                <div class="h-80">
                    <canvas id="transactionTimeChart" data-labels="{{ json_encode($timeChartLabels) }}"
                        data-shared="{{ json_encode($sharedTimeData) }}"
                        data-received="{{ json_encode($receivedTimeData) }}"></canvas>
                </div>
            </div>

            <!-- Category Distribution Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Phân bố theo danh mục</h2>
                <div class="h-80">
                    <canvas id="categoryChart" data-labels="{{ json_encode($categoryLabels) }}"
                        data-values="{{ json_encode($categoryData) }}"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold">Giao dịch gần đây</h2>
                    <a href="{{ route('transactions.index') }}"
                        class="text-green-600 hover:text-green-700 text-sm font-medium transition duration-150 ease-in-out">Xem
                        tất cả</a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Món
                                đồ</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh
                                mục</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người
                                dùng</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng
                                thái</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($transactions as $transaction)
                            <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if (auth()->user()->id == $transaction->giver->id)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Đã chia sẻ
                                        </span>
                                    @else
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Đã nhận
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <a href="{{ route('item.detail', $transaction->post->id) }}"
                                        class="hover:text-green-600 transition duration-150 ease-in-out">
                                        {{ $transaction->post->title }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->post->category->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    @if (auth()->user()->id == $transaction->giver->id)
                                        <a href="{{ route('user.profile', $transaction->receiver->id) }}"
                                            class="hover:text-green-600 transition duration-150 ease-in-out">
                                            {{ $transaction->receiver->name }}</a>
                                    @else
                                        <a href="{{ route('user.profile', $transaction->giver->id) }}"
                                            class="hover:text-green-600 transition duration-150 ease-in-out">
                                            {{ $transaction->giver->name }}</a>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($transaction->status == 'pending')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Đang chờ
                                        </span>
                                    @elseif ($transaction->status == 'accepted')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Đã chấp nhận
                                        </span>
                                    @elseif ($transaction->status == 'completed')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Đã hoàn thành
                                        </span>
                                    @elseif ($transaction->status == 'rejected')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Đã từ chối
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Không có giao dịch nào trong khoảng thời gian đã chọn
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <div class="text-sm text-gray-500 mb-4 sm:mb-0">
                        Hiển thị {{ $transactions->firstItem() ?? 0 }}-{{ $transactions->lastItem() ?? 0 }} của
                        {{ $transactions->total() ?? 0 }} giao dịch
                    </div>
                    {{ $transactions->appends(request()->except('page'))->links('pagination::tailwind') }}
                </div>
            </div>
        </div>

        <!-- Export Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Xuất dữ liệu</h2>
            <p class="text-gray-600 mb-4">Xuất dữ liệu thống kê giao dịch của bạn để sử dụng trong các ứng dụng khác.</p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('transactions.export.excel', request()->all()) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md flex items-center transition duration-150 ease-in-out">
                    <i class="fas fa-file-excel mr-2"></i> Xuất Excel
                </a>
                <a href="{{ route('transactions.export.csv', request()->all()) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md flex items-center transition duration-150 ease-in-out">
                    <i class="fas fa-file-csv mr-2"></i> Xuất CSV
                </a>
                <a href="{{ route('transactions.export.pdf', request()->all()) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md flex items-center transition duration-150 ease-in-out">
                    <i class="fas fa-file-pdf mr-2"></i> Xuất PDF
                </a>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Add JavaScript for date range functionality
        document.getElementById('dateRange').addEventListener('change', function() {
            const customContainer = document.getElementById('customDateContainer');
            if (this.value === 'custom') {
                customContainer.classList.remove('hidden');
            } else {
                customContainer.classList.add('hidden');
            }
        });

        document.getElementById('applyDateFilter').addEventListener('click', function() {
            const dateRange = document.getElementById('dateRange').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            
            let url = new URL(window.location.href);
            url.searchParams.set('date_range', dateRange);
            
            if (dateRange === 'custom') {
                if (startDate) url.searchParams.set('start_date', startDate);
                if (endDate) url.searchParams.set('end_date', endDate);
            } else {
                url.searchParams.delete('start_date');
                url.searchParams.delete('end_date');
            }
            
            window.location.href = url.toString();
        });
    </script>
@endsection
