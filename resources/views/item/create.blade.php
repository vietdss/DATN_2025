@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Đăng bài</h1>
        <!-- Create Post Form -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="p-6">

                <form action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input hidden type="text" name="user_id" value="{{ Auth::user()->id }}">
                    <!-- Basic Info -->
                  <!-- Basic Info -->
<div class="mb-6">
    <h2 class="text-xl font-semibold mb-4">Thông tin cơ bản</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Title -->
        <div>
            <label for="title" class="block text-gray-700 text-sm font-medium mb-2">
                Tiêu đề <span class="text-red-500">*</span>
            </label>
            <input type="text" id="title" name="title" required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
        </div>
        
        <!-- Category -->
        <div>
            <label for="category" class="block text-gray-700 text-sm font-medium mb-2">
                Danh mục <span class="text-red-500">*</span>
            </label>
            <select id="category" name="category_id" required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                <option value="">Chọn danh mục</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Quantity -->
        <div>
            <label for="quantity" class="block text-gray-700 text-sm font-medium mb-2">
                Số lượng <span class="text-red-500">*</span>
            </label>
            <input type="number" id="quantity" name="quantity" required min="1"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
        </div>

        <!-- Expiration Date -->
      <!-- Expiration Date -->
<div>
    <label for="expiration_date" class="block text-gray-700 text-sm font-medium mb-2">
        Ngày hết hạn <span class="text-red-500">*</span>
    </label>
    <input type="datetime-local" id="expiration_date" name="expired_at"
        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
</div>

    </div>
</div>


                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-gray-700 text-sm font-medium mb-2">Mô tả <span
                                class="text-red-500">*</span></label>
                        <textarea id="description" name="description" rows="5" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"></textarea>
                        <p class="text-gray-500 text-sm mt-1">Mô tả chi tiết về món đồ bạn muốn chia sẻ, tình trạng, số
                            lượng, hạn sử dụng (nếu có)...</p>
                    </div>
                    
                    <!-- Image Upload Section - Improved -->
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-4">Hình ảnh</h2>
                        
                        <!-- Drag & Drop Zone -->
                        <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center transition-all hover:border-green-500 cursor-pointer">
                            <input type="file" id="imageInput" name="images[]" multiple accept="image/*" class="hidden">
                            <input type="hidden" id="imageData" name="imageData">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                <p class="text-gray-700 mb-1">Kéo thả hình ảnh vào đây hoặc nhấp để chọn</p>
                                <p class="text-gray-500 text-sm">Hỗ trợ JPG, PNG, GIF (Tối đa 4 hình, mỗi hình không quá 5MB)</p>
                            </div>
                        </div>
                        
                        <!-- Image Preview Section -->
                        <div class="mt-4">
                            <div id="imageCounter" class="text-sm text-gray-600 mb-2 hidden">
                                <span id="imageCount">0</span>/4 hình ảnh đã chọn
                            </div>
                            <div id="imagePreview" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4"></div>
                        </div>
                    </div>
                    
                    <!-- Location -->
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-4">Địa điểm</h2>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="relative">
                                <label for="address" class="block text-gray-700 text-sm font-medium mb-2">
                                    Địa chỉ cụ thể
                                </label>
                                <input type="text" id="address" name="address"
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
                                <input type="hidden" id="location" name="location" value='{"lat":10.7769,"lng":106.7009}'>
                                <p class="text-gray-500 text-sm mt-1">Nhấp vào bản đồ để chọn vị trí</p>
                            </div>
                        </div>
                    </div>
                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                        <button type="button" onclick="history.back()" 
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Quay lại
                        </button>
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Đăng bài
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
<script>
    // Any page-specific data can go here
</script>
@endpush
