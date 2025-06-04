@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Profile Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="bg-green-600 h-32 md:h-48"></div>
            <div class="px-6 py-4 md:px-8 md:py-6">
                <div class="flex flex-col md:flex-row items-center md:items-end -mt-16 md:-mt-24">
                    @if($user->profile_image)
                        <img src="{{ $user->profile_image }}" alt="{{ $user->name }}"
                            class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-white object-cover">
                    @else
                        <img src="/placeholder.svg?height=150&width=150" alt="{{ $user->name }}"
                            class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-white">
                    @endif
                    <div class="mt-4 md:mt-0 md:ml-6 text-center md:text-left">
                        <h1 class="text-2xl md:text-3xl font-bold">{{ $user->name }}</h1>
                        <p class="text-gray-600">Thành viên từ {{ $user->created_at->format('m/Y') }}</p>
                    </div>
                    <div class="mt-4 md:mt-0 md:ml-auto">
                        @if(Auth::id() === $user->id)
                            <a href="{{ route('user.setting') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md">
                                <i class="fas fa-edit mr-2"></i> Chỉnh sửa hồ sơ
                            </a>
                        @else
                            <a href="{{ route('messages.show', ['userId' => $user->id]) }}" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md inline-flex items-center">
                                <i class="fas fa-envelope mr-2"></i> Nhắn tin
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left Sidebar -->
            <div class="md:col-span-1">
                <!-- User Info -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-4">Thông tin cá nhân</h2>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt w-6 text-gray-500"></i>
                                <span class="ml-2">{{ $user->address ?? 'Chưa cập nhật địa chỉ' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-envelope w-6 text-gray-500"></i>
                                <span class="ml-2">{{ $user->email }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone w-6 text-gray-500"></i>
                                <span class="ml-2">{{ $user->phone ?? 'Chưa cập nhật số điện thoại' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt w-6 text-gray-500"></i>
                                <span class="ml-2">Tham gia từ {{ $user->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-4">Thống kê</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">{{ $sharedCount }}</div>
                                <div class="text-gray-600">Đã chia sẻ</div>
                            </div>
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">{{ $receivedCount }}</div>
                                <div class="text-gray-600">Đã nhận</div>
                            </div>
                        </div>
                        @if(Auth::id() === $user->id)
                            <div class="mt-4 text-center">
                                <a href="{{ route('statistics') }}"
                                    class="inline-flex items-center text-green-600 hover:text-green-700">
                                    <i class="fas fa-chart-bar mr-1"></i> Xem thống kê giao dịch chi tiết
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="md:col-span-2">
                <!-- Tabs -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b">
                        <nav class="flex">
                            <button class="px-6 py-3 border-b-2 border-green-600 text-green-600 font-medium">Đồ đã đăng</button>
                        </nav>
                    </div>

                    <!-- Items Grid -->
                    <div class="p-6">
                        <!-- Filter Form -->
                        <form method="GET" action="{{ route('user.profile', $user->id) }}" class="mb-6">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                                <h2 class="text-xl font-bold">Đồ đã đăng ({{ $items->total() }})</h2>
                                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                                    <div class="relative w-full sm:w-64">
                                        <input type="text" name="search" value="{{ $currentSearch }}" 
                                            placeholder="Tìm kiếm đồ dùng..." 
                                            class="w-full px-3 py-1 border border-gray-300 rounded-md text-sm pl-8">
                                        <i class="fas fa-search absolute left-2.5 top-2 text-gray-400"></i>
                                    </div>
                                    <select name="status" class="px-3 py-1 border border-gray-300 rounded-md text-sm">
                                        <option value="">Tất cả</option>
                                        <option value="Còn hàng" {{ $currentStatus === 'Còn hàng' ? 'selected' : '' }}>Còn hàng</option>
                                        <option value="Hết hàng" {{ $currentStatus === 'Hết hàng' ? 'selected' : '' }}>Hết hàng</option>
                                        <option value="Chờ duyệt" {{ $currentStatus === 'Chờ duyệt' ? 'selected' : '' }}>Chờ duyệt</option>
                                    </select>
                                    <button type="submit" class="px-4 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                                        Lọc
                                    </button>
                                    @if($currentSearch || $currentStatus)
                                        <a href="{{ route('user.profile', $user->id) }}" 
                                           class="px-4 py-1 bg-gray-500 text-white rounded-md hover:bg-gray-600 text-sm">
                                            Xóa bộ lọc
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="itemsGrid">
                            @forelse($items as $item)
                                <div class="item-card border rounded-lg overflow-hidden flex cursor-pointer" 
                                     onclick="window.location='{{ route('item.detail', ['id' => $item->id]) }}'">
                                    <img src="{{ optional($item->images->first())->image_url }}"
                                        alt="{{ $item->title }}" class="w-24 h-24 object-cover">
                                    <div class="p-3 flex-1">
                                        <div class="flex justify-between">
                                            <h3 class="font-semibold">{{ $item->title }}</h3>
                                            @if($item->is_approved == false)
                                                <span class="item-status text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Chờ duyệt</span>
                                            @elseif($item->is_approved == true && $item->status != 'Taken')
                                                <span class="item-status text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Còn hàng</span>
                                            @else
                                                <span class="item-status text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Hết hàng</span>
                                            @endif
                                        </div>
                                        <p class="text-gray-500 text-sm mt-1">{{ $item->created_at->diffForHumans() }}</p>
                                        <div class="flex justify-between items-center mt-2">
                                            <div class="ml-auto">
                                                @if (Auth::id() === $user->id)
                                                    <a href="{{ route('item.edit', $item->id) }}" class="text-gray-500 hover:text-gray-700 text-sm mr-2">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('item.destroy', $item->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" onclick="event.stopPropagation();" class="text-gray-500 hover:text-red-600 text-sm delete-item-btn">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <a href="{{ route('item.detail', $item->id) }}" class="text-gray-500 hover:text-blue-600 text-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 text-center text-gray-500 mt-4">
                                    @if($currentSearch || $currentStatus)
                                        Không có kết quả nào phù hợp với bộ lọc
                                    @else
                                        Chưa có đồ dùng nào được đăng
                                    @endif
                                </div>
                            @endforelse
                        </div>

                        <!-- Pagination -->
                        @if($items->hasPages())
                            <div class="mt-8 flex justify-center">
                                <nav class="inline-flex rounded-md shadow">
                                    @if ($items->onFirstPage())
                                        <span class="px-3 py-2 rounded-l-md border border-gray-300 bg-white text-gray-500 flex items-center justify-center">
                                            <i class="fas fa-chevron-left"></i>
                                        </span>
                                    @else
                                        <a href="{{ $items->previousPageUrl() }}"
                                            class="px-3 py-2 rounded-l-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 flex items-center justify-center">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    @endif

                                    @php
                                        $currentPage = $items->currentPage();
                                        $lastPage = $items->lastPage();
                                        $start = max(1, $currentPage - 1);
                                        $end = min($lastPage, $currentPage + 1);
                                    @endphp

                                    @if ($start > 1)
                                        <a href="{{ $items->url(1) }}"
                                            class="px-3 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">1</a>
                                        @if ($start > 2)
                                            <span class="px-3 py-2 text-gray-500">...</span>
                                        @endif
                                    @endif

                                    @for ($page = $start; $page <= $end; $page++)
                                        <a href="{{ $items->url($page) }}"
                                            class="px-3 py-2 border border-gray-300 
                                                      {{ $page == $currentPage ? 'bg-green-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                                            {{ $page }}
                                        </a>
                                    @endfor

                                    @if ($end < $lastPage)
                                        @if ($end < $lastPage - 1)
                                            <span class="px-3 py-2 text-gray-500">...</span>
                                        @endif
                                        <a href="{{ $items->url($lastPage) }}"
                                            class="px-3 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">{{ $lastPage }}</a>
                                    @endif

                                    @if ($items->hasMorePages())
                                        <a href="{{ $items->nextPageUrl() }}"
                                            class="px-3 py-2 rounded-r-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 flex items-center justify-center">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    @else
                                        <span class="px-3 py-2 rounded-r-md border border-gray-300 bg-white text-gray-500 flex items-center justify-center">
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    @endif
                                </nav>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal xác nhận xóa -->
    <div id="deleteConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 transform transition-all scale-95 opacity-0">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Xác nhận xóa</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" id="closeDeleteModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mb-5">
                <p class="text-gray-600" id="deleteConfirmMessage">Bạn có chắc chắn muốn xóa món đồ này?</p>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300" id="cancelDelete">
                    Hủy bỏ
                </button>
                <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700" id="confirmDelete">
                    Xóa
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const deleteButtons = document.querySelectorAll(".delete-item-btn");
            const modal = document.getElementById("deleteConfirmModal");
            
            if (!modal) return;
            
            const modalBox = modal.querySelector(".p-6");
            const closeModalBtn = document.getElementById("closeDeleteModal");
            const cancelDeleteBtn = document.getElementById("cancelDelete");
            const confirmDeleteBtn = document.getElementById("confirmDelete");
          
            let selectedForm = null;
          
            // Mở modal xác nhận
            deleteButtons.forEach(button => {
                button.addEventListener("click", function () {
                    selectedForm = this.closest("form");
                    modal.classList.remove("hidden");
                    setTimeout(() => {
                        modalBox.classList.remove("scale-95", "opacity-0");
                        modalBox.classList.add("scale-100", "opacity-100");
                    }, 10);
                });
            });
          
            function closeModal() {
                modalBox.classList.remove("scale-100", "opacity-100");
                modalBox.classList.add("scale-95", "opacity-0");
                setTimeout(() => {
                    modal.classList.add("hidden");
                }, 150);
            }
          
            if (closeModalBtn) {
                closeModalBtn.addEventListener("click", closeModal);
            }
            
            if (cancelDeleteBtn) {
                cancelDeleteBtn.addEventListener("click", closeModal);
            }
          
            // Xác nhận xóa bằng fetch
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener("click", function () {
                    if (selectedForm) {
                        const formData = new FormData(selectedForm);
                        const action = selectedForm.getAttribute("action");
          
                        fetch(action, {
                            method: "POST",
                            body: formData,
                            headers: {
                                "X-Requested-With": "XMLHttpRequest",
                                "X-CSRF-TOKEN": formData.get("_token")
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error("Delete failed");
                            }
                            return response.json().catch(() => ({}));
                        })
                        .then(() => {
                            closeModal();
                            showNotification('Xóa thành công', 'success');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        })
                        .catch(() => {
                            showNotification('Xóa không thành công. Vui lòng thử lại sau.', 'error');
                            closeModal();
                        });
                    }
                });
            }
        });
          
        // Hiển thị thông báo
        function showNotification(message, type = 'info') {
            let container = document.getElementById('notification-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'notification-container';
                container.className = 'fixed bottom-4 right-4 z-50 flex flex-col space-y-2';
                document.body.appendChild(container);
            }
          
            const notification = document.createElement('div');
          
            let bgColor, iconClass;
            switch (type) {
                case 'success':
                    bgColor = 'bg-green-100 border-l-4 border-green-500 text-green-700';
                    iconClass = 'fas fa-check-circle text-green-500';
                    break;
                case 'error':
                    bgColor = 'bg-red-100 border-l-4 border-red-500 text-red-700';
                    iconClass = 'fas fa-times-circle text-red-500';
                    break;
                default:
                    bgColor = 'bg-blue-100 border-l-4 border-blue-500 text-blue-700';
                    iconClass = 'fas fa-info-circle text-blue-500';
            }
          
            notification.className = `${bgColor} p-4 rounded shadow-md flex items-center transform transition-all duration-300 translate-x-full`;
            notification.innerHTML = `
                <i class="${iconClass} mr-3 text-lg"></i>
                <span>${message}</span>
                <button class="ml-auto text-gray-400 hover:text-gray-600" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;
          
            container.appendChild(notification);
          
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 10);
          
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 5000);
        }
    </script>
@endsection
