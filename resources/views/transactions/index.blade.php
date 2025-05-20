@extends('layouts.app')

@section('content')
<main class="container mx-auto px-4 py-8">
    <section>
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Quản lý yêu cầu nhận đồ</h1>
        <!-- Tab chuyển đổi -->
        <div class="mb-6 flex gap-2">
            <button id="tab-received" class="px-4 py-2 rounded-md bg-green-500 text-white font-medium focus:outline-none">Yêu cầu nhận đồ</button>
            <button id="tab-sent" class="px-4 py-2 rounded-md bg-gray-200 text-gray-700 font-medium focus:outline-none">Yêu cầu đã gửi</button>
        </div>
        <!-- Thống kê -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md p-4 flex items-center border-l-4 border-yellow-400 transform transition-transform duration-300 hover:-translate-y-1">
                <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center mr-4">
                    <i class="fas fa-clock text-yellow-500"></i>
                </div>
                <div>
                    <h3 class="text-sm text-gray-500 mb-1">Đang chờ</h3>
                    <p class="text-2xl font-bold text-gray-800" id="pending-count">0</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 flex items-center border-l-4 border-green-400 transform transition-transform duration-300 hover:-translate-y-1">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mr-4">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div>
                    <h3 class="text-sm text-gray-500 mb-1">Đã chấp nhận</h3>
                    <p class="text-2xl font-bold text-gray-800" id="accepted-count">0</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 flex items-center border-l-4 border-red-400 transform transition-transform duration-300 hover:-translate-y-1">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mr-4">
                    <i class="fas fa-times-circle text-red-500"></i>
                </div>
                <div>
                    <h3 class="text-sm text-gray-500 mb-1">Đã từ chối</h3>
                    <p class="text-2xl font-bold text-gray-800" id="rejected-count">0</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 flex items-center border-l-4 border-blue-400 transform transition-transform duration-300 hover:-translate-y-1">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                    <i class="fas fa-check-double text-blue-500"></i>
                </div>
                <div>
                    <h3 class="text-sm text-gray-500 mb-1">Đã hoàn thành</h3>
                    <p class="text-2xl font-bold text-gray-800" id="completed-count">0</p>
                </div>
            </div>
        </div>
        <!-- Bộ lọc -->
        <div class="bg-white rounded-lg shadow-md p-5 mb-6">
            <div class="flex flex-wrap gap-4">
                <div class="w-full md:w-auto flex-1 min-w-[200px]">
                    <div class="relative">
                        <input type="text" id="search-input" placeholder="Tìm kiếm theo tên, mô tả..." class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button id="search-btn" class="absolute right-0 top-0 h-full px-3 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="w-full md:w-auto md:flex-1 min-w-[150px]">
                    <select id="status-filter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="all">Tất cả trạng thái</option>
                        <option value="pending">Đang chờ</option>
                        <option value="accepted">Đã chấp nhận</option>
                        <option value="rejected">Đã từ chối</option>
                        <option value="completed">Đã hoàn thành</option>
                    </select>
                </div>
                <div class="w-full md:w-auto md:flex-1 min-w-[150px]">
                    <select id="date-filter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="all">Tất cả thời gian</option>
                        <option value="today">Hôm nay</option>
                        <option value="week">Tuần này</option>
                        <option value="month">Tháng này</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- Danh sách yêu cầu nhận đồ -->
        <div id="received-requests-section" class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người yêu cầu</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên món đồ</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày yêu cầu</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="requests-list" class="bg-white divide-y divide-gray-200" data-transactions="{{ json_encode($transactions) }}">
                        <!-- Dữ liệu sẽ được render bằng JS -->
                    </tbody>
                </table>
            </div>
            <div id="no-requests" class="hidden py-12 text-center">
                <div class="inline-block p-4 rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-inbox text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-700 mb-2">Không có yêu cầu nào</h3>
                <p class="text-gray-500 mb-4">Hiện tại bạn chưa có yêu cầu nhận đồ nào.</p>
            </div>
            <div class="px-6 py-4 bg-white border-t border-gray-200 flex items-center justify-between">
                <button id="prev-page" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-chevron-left mr-1"></i> Trước
                </button>
                <span id="page-info" class="text-sm text-gray-700">Trang 1 / 1</span>
                <button id="next-page" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    Sau <i class="fas fa-chevron-right ml-1"></i>
                </button>
            </div>
        </div>
        <!-- Danh sách yêu cầu đã gửi -->
        <div id="sent-requests-section" class="bg-white rounded-lg shadow-md overflow-hidden hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên món đồ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày gửi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="sent-requests-list" class="bg-white divide-y divide-gray-200" data-sent-transactions="{{ json_encode($sentTransactions ?? []) }}">
                        <!-- Dữ liệu sẽ được render bằng JS -->
                    </tbody>
                </table>
            </div>
            <div id="no-sent-requests" class="hidden py-12 text-center">
                <div class="inline-block p-4 rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-inbox text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-700 mb-2">Không có yêu cầu nào</h3>
                <p class="text-gray-500 mb-4">Hiện tại bạn chưa gửi yêu cầu nào.</p>
            </div>
            <div class="px-6 py-4 bg-white border-t border-gray-200 flex items-center justify-between">
                <button id="prev-sent-page" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-chevron-left mr-1"></i> Trước
                </button>
                <span id="sent-page-info" class="text-sm text-gray-700">Trang 1 / 1</span>
                <button id="next-sent-page" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    Sau <i class="fas fa-chevron-right ml-1"></i>
                </button>
            </div>
        </div>
    </section>
</main>

<!-- Modal xác nhận -->
<div id="confirm-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <!-- Modal -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div id="modal-icon" class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                        <!-- Icon sẽ được thêm bằng JavaScript -->
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            <!-- Tiêu đề sẽ được thêm bằng JavaScript -->
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="modal-message">
                                <!-- Nội dung sẽ được thêm bằng JavaScript -->
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="confirm-button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                    <!-- Nút xác nhận sẽ được tùy chỉnh bằng JavaScript -->
                </button>
                <button type="button" id="cancelDelete" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Hủy
                </button>
            </div>
        </div>
    </div>
</div>
@endsection