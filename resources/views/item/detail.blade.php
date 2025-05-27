@extends('layouts.app')

@section('content')
  <main class="container mx-auto px-4 py-8">
    <!-- Breadcrumbs -->
    <nav class="text-sm mb-6">
    <ol class="list-none p-0 inline-flex">
      <li class="flex items-center">
      <a href="{{ route('home') }}" class="text-gray-500 hover:text-green-600">Trang chủ</a>
      <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
      </li>
      <li class="flex items-center">
      <a href="{{ route('item.index') }}" class="text-gray-500 hover:text-green-600">Khám phá</a>
      <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
      </li>
      <li class="flex items-center">
      <a href="{{ route('item.index', ['category_id' => $item->category->id]) }}"
        class="text-gray-500 hover:text-green-600">{{ $item->category->name }}</a>
      <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
      </li>
      <li class="text-gray-700">{{ $item->title }}</li>
    </ol>
    </nav>

    <!-- Item Details -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
      <!-- Image Gallery -->
      <div>
      <div class="mb-4">
        <img src="{{ optional($item->images->first())->image_url }}" alt="{{ $item->title }}"
        class="w-full h-80 object-cover rounded-lg" id="mainImage">
      </div>
      <div class="grid grid-cols-4 gap-2" id="thumbnails">
        @foreach ($item->images as $key => $image)
      <img src="{{ optional($image)->image_url }}" alt="{{ $item->title }} {{ $key + 1 }}"
      class="w-full h-20 object-cover rounded cursor-pointer {{ $key === 0 ? 'border-2 border-green-500' : 'hover:opacity-80' }}"
      onclick="changeImage(this, '{{ optional($image)->image_url }}')">
      @endforeach
      </div>
      </div>

      <!-- Item Info -->
      <div>
      <div class="flex justify-between items-start mb-4">
        <div>
        <span
          class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">{{ $item->category->name }}</span>
        <h1 class="text-3xl font-bold mt-2">{{ $item->title }}</h1>
        </div>
      </div>

      <div class="mb-6">
        <div class="flex items-center text-gray-500 mb-2">
        <i class="fas fa-map-marker-alt mr-2"></i>
        <span class="location-text" data-location="{{ $item->location }}"> <span>Đang tải...</span></span>
        </div>

        <div class="flex items-center text-gray-500 mb-2">
        <i class="far fa-clock mr-2"></i>
        <span>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
        </span>
        </div>
        <div class="flex items-center text-gray-500 mb-2">
        <i class="fas fa-cubes mr-2"></i>
        <span>Số lượng: {{ $item->quantity }}</span>
        </div>

        <!-- Ngày hết hạn -->
        <div class="flex items-center text-gray-500 mb-2">
        <i class="fas fa-calendar-alt mr-2"></i>
        <span>Ngày hết hạn:

{{ $item->expired_at ? $item->expired_at->format('Y-m-d H:i') : 'Không có' }}

        </span>
        </div>
        <div class="flex items-center text-gray-500 mb-2">
        <i class="fas fa-tag mr-2"></i>
        <span>{{ $item->status }}</span>
        </div>
      </div>

      <!-- User Info -->
      <div class="border-t border-gray-200 pt-4 mb-6">
        <div class="flex items-center">
        <img src="{{ $item->user->profile_image }}" alt="Người đăng" class="w-12 h-12 rounded-full mr-4">
        <div>
          <h3 class="font-semibold"><a
            href="{{ route('user.profile', $item->user->id) }}">{{ $item->user->name }}</a></h3>
        </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
        @if(Auth::id() === $item->user_id)
      <a href="{{ route('item.edit', $item->id) }}"
      class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-md flex-1 text-center">
      <i class="fas fa-edit mr-2"></i> Sửa
      </a>
      <form id="deleteItemForm" action="{{ route('item.destroy', $item->id) }}" method="POST" class="flex-1">
      @csrf
      @method('DELETE')
      <button type="button"
        class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md delete-item-btn">
        <i class="fas fa-trash-alt mr-2"></i> Xóa
      </button>
      </form>
      @else
    <button onclick="window.location.href='{{ route('messages.show', $item->user_id) }}'"
class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md flex-1 flex justify-center items-center">
  <i class="fas fa-comment-alt mr-2"></i> Nhắn tin
</button>

        <!-- Nút yêu cầu / hủy -->
        <div id="request-action" class="flex-1">
        @if ($userTransaction && $userTransaction->status === 'completed')
      <button
        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md w-full flex justify-center items-center">
        Yêu cầu đã hoàn thành
      </button>

      @elseif ($userTransaction && $userTransaction->status === 'rejected')
      <button
        class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md w-full flex justify-center items-center">
        Yêu cầu bị từ chối
      </button>

      @elseif ($userTransaction)
      <button onclick="cancelRequest({{ $item->id }})"
        class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md w-full flex justify-center items-center">
        <i class="fas fa-times mr-2"></i> Hủy yêu cầu
      </button>

      @else
      <button onclick="openQuantityModal({{ $item->id }})"
        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md w-full flex justify-center items-center">
        <i class="fas fa-phone-alt mr-2"></i> Yêu cầu
      </button>
      @endif
        </div>

      @endif
      </div>

      </div>
    </div>
    </div>

    <!-- Item Description -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
    <div class="p-6">
      <h2 class="text-xl font-bold mb-4">Mô tả chi tiết</h2>
      <div class="text-gray-700">
      {{ $item->description }}
      </div>
    </div>
    </div>


    <!-- Related Items - Now using KNN algorithm -->
    <div class="mb-8">
    <h2 class="text-2xl font-bold mb-6">Các món đồ tương tự</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      @forelse($similarItems as $item)

      <div onclick="window.location='{{ route('item.detail', ['id' => $item->id]) }}'"
      class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300 cursor-pointer">
      <img src="{{ optional($item->images->first())->image_url}}" alt="Hình ảnh" class="w-full h-40 object-cover">
      <div class="p-4">
      <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded mb-2">
      {{ $item->category->name ?? 'Không có danh mục' }}
      </span>
      <h3 class="text-lg font-semibold mb-2">{{ $item->title }}</h3>
      <div class="flex justify-between items-center">
      <span class="text-gray-500 text-sm flex items-center location-text" data-location="{{ $item->location }}">
        <i class="fas fa-map-marker-alt mr-1"></i>
        <span>Đang tải...</span>
      </span>
      <span class="text-gray-500 text-sm whitespace-nowrap">
        {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
      </span>
      </div>
      </div>
      </div>
    @empty
      <div class="col-span-4 text-center py-8">
      <p class="text-gray-500">Không tìm thấy sản phẩm tương tự.</p>
      </div>
    @endforelse
    </div>
    </div>
    <!-- Quantity Input Modal -->
    <div id="quantity-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
      <h2 class="text-lg font-semibold mb-4">Nhập số lượng yêu cầu</h2>
      <input type="number" id="quantity-input" class="w-full border border-gray-300 rounded px-3 py-2 mb-4"
      placeholder="Số lượng" min="1">
      <div class="flex justify-end space-x-2">
      <button onclick="closeQuantityModal()"
        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">Hủy</button>
      <button onclick="confirmSendRequest()"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Gửi</button>
      </div>
    </div>
    </div>

    <!-- Modal xác nhận xóa -->
    <div id="deleteConfirmModal"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
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
      <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300"
        id="cancelDelete">
        Hủy bỏ
      </button>
      <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700" id="confirmDelete">
        Xóa
      </button>
      </div>
    </div>
    </div>
  </main>
    <div id="quantityErrorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 {{ session('error') ? '' : 'hidden' }}">
    <div class="bg-white rounded-lg p-6 max-w-md w-full shadow-lg">
        <div class="flex items-center mb-4">
            <i class="fas fa-exclamation-triangle text-red-500 text-3xl mr-3"></i>
            <h2 class="text-xl font-bold text-red-600">Không thể cập nhật sản phẩm</h2>
        </div>
        <div class="mb-6 text-gray-700">
            {!! session('error') !!}
        </div>
        <div class="flex justify-end space-x-2">
            <a href="{{ route('transactions.index') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Xử lý yêu cầu
            </a>
            <button type="button" onclick="closeQuantityErrorModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                Đóng
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function closeQuantityErrorModal() {
    document.getElementById('quantityErrorModal').classList.add('hidden');
}
@if(session('error'))
    // Tự động cuộn lên đầu trang khi có lỗi
    window.scrollTo({ top: 0, behavior: 'smooth' });
@endif
document.querySelector('.delete-item-btn').addEventListener('click', function() {
    document.getElementById('deleteConfirmModal').classList.remove('hidden');
    setTimeout(() => {
        document.querySelector('#deleteConfirmModal > div').classList.remove('scale-95', 'opacity-0');
        document.querySelector('#deleteConfirmModal > div').classList.add('scale-100', 'opacity-100');
    }, 10);
});
document.getElementById('cancelDelete').onclick = closeDeleteModal;
document.getElementById('closeDeleteModal').onclick = closeDeleteModal;
function closeDeleteModal() {
    document.getElementById('deleteConfirmModal').classList.add('hidden');
    document.querySelector('#deleteConfirmModal > div').classList.add('scale-95', 'opacity-0');
    document.querySelector('#deleteConfirmModal > div').classList.remove('scale-100', 'opacity-100');
}
document.getElementById('confirmDelete').onclick = function() {
    document.getElementById('deleteItemForm').submit();
};
function closeQuantityErrorModal() {
    document.getElementById('quantityErrorModal').classList.add('hidden');
}
@if(session('error'))
    window.scrollTo({ top: 0, behavior: 'smooth' });
@endif
</script>
@endpush