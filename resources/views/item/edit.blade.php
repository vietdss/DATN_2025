@extends('layouts.app')


@section('content')

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Cập nhật bài đăng</h1>
        <!-- Edit Post Form -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="p-6">

                <form action="{{ route('item.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input hidden type="text" name="user_id" value="{{ Auth::user()->id }}">
                   <!-- Basic Info -->
<div class="mb-6">
    <h2 class="text-xl font-semibold mb-4">Thông tin cơ bản</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Title -->
        <div>
            <label for="title" class="block text-gray-700 text-sm font-medium mb-2">Tiêu đề <span class="text-red-500">*</span></label>
            <input type="text" id="title" name="title" required value="{{ $item->title }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
        </div>

        <!-- Category -->
        <div>
            <label for="category" class="block text-gray-700 text-sm font-medium mb-2">Danh mục <span class="text-red-500">*</span></label>
            <select id="category" name="category_id" required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                <option value="">Chọn danh mục</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Quantity -->
        <div>
            <label for="quantity" class="block text-gray-700 text-sm font-medium mb-2">Số lượng <span class="text-red-500">*</span></label>
            <input type="number" id="quantity" name="quantity" required min="1" value="{{ $item->quantity ?? 1 }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
        </div>

        <!-- Expiration Date -->
        <div>
            <label for="expiration_date" class="block text-gray-700 text-sm font-medium mb-2">Ngày hết hạn</label>
            <input type="datetime-local" id="expiration_date" name="expired_at" 
                value="{{ $item->expired_at ? $item->expired_at->format('Y-m-d\TH:i') : '' }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
        </div>
    </div>
</div>


                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-gray-700 text-sm font-medium mb-2">Mô tả <span
                                class="text-red-500">*</span></label>
                        <textarea id="description" name="description" rows="5" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">{{ $item->description }}</textarea>
                        <p class="text-gray-500 text-sm mt-1">Mô tả chi tiết về món đồ bạn muốn chia sẻ, tình trạng, số
                            lượng, hạn sử dụng (nếu có)...</p>
                    </div>
                    
                    <!-- Image Upload Section - Improved -->
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-4">Hình ảnh</h2>
                        
                        <!-- Drag & Drop Zone -->
                        <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center transition-all hover:border-green-500 cursor-pointer">
                            <input type="file" id="imageInput" name="new_images[]" multiple accept="image/*" class="hidden">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                <p class="text-gray-700 mb-1">Kéo thả hình ảnh vào đây hoặc nhấp để chọn</p>
                                <p class="text-gray-500 text-sm">Hỗ trợ JPG, PNG, GIF (Tối đa 4 hình, mỗi hình không quá 5MB)</p>
                            </div>
                        </div>
                        
                        <!-- Image Preview Section -->
                        <div class="mt-4">
                            <div id="imageCounter" class="text-sm text-gray-600 mb-2">
                                <span id="imageCount">{{ count($item->images) }}</span>/4 hình ảnh đã chọn
                            </div>
                            <div id="imagePreview" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach($item->images as $image)
                                <div class="existing-image relative group rounded-lg overflow-hidden shadow-sm border border-gray-200" style="aspect-ratio: 1/1;" data-id="{{ $image->id }}">
                                    <img src="{{ optional($image)->image_url}}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200"></div>
                                    <button type="button" class="delete-existing-image absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-md transform hover:scale-110">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Hidden input for deleted images -->
                        <div id="deletedImagesContainer"></div>
                    </div>
                    
                    <!-- Location -->
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-4">Địa điểm</h2>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="relative">
                                <label for="address" class="block text-gray-700 text-sm font-medium mb-2">
                                    Địa chỉ cụ thể
                                </label>
                                <input type="text" id="address" name="address" value="{{ $item->address ?? '' }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                    autocomplete="off">
                                <div id="addressSuggestions"
                                    class="absolute left-0 right-0 bg-white border border-gray-300 rounded-md shadow-md hidden max-h-60 overflow-y-auto z-[9999]">
                                    <div class="px-3 py-2 cursor-pointer transition duration-200 hover:bg-gray-200">
                                        Gợi ý địa chỉ
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Map -->
                        <div class="mt-4">
                            <div id="create-map" class="h-64 bg-gray-200 rounded-lg flex items-center justify-center" style="z-index:1;">
                                <input type="hidden" id="location" name="location" value="{{ $item->location }}">
                                <p class="text-gray-500 text-sm mt-1">Nhấp vào bản đồ để chọn vị trí</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                        <a href="javascript:void(0)" 
                        onclick="history.back()" 
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 text-center">
                            Hủy
                        </a>
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Toast Notification -->
    <div id="toast-container" class="fixed top-4 right-4 z-50"></div>
    <div id="quantityErrorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 {{ session('error') ? '' : 'hidden' }}">
    <div class="bg-white rounded-lg p-6 max-w-md w-full shadow-lg">
        <div class="flex items-center mb-4">
            <i class="fas fa-exclamation-triangle text-red-500 text-3xl mr-3"></i>
            <h2 class="text-xl font-bold text-red-600">Không thể cập nhật số lượng</h2>
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
</script>
@endpush