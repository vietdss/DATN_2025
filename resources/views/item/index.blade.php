@extends('layouts.app')

@section('content')
    <main class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-6">Khám phá</h1>

        <div class="flex flex-col md:flex-row gap-6">
            <div class="w-full md:w-1/4">
                <div class="filter-sidebar bg-white rounded-lg shadow-md p-4 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Bộ lọc</h2>
                    <form id="searchFilterForm" method="get">
                        <div class="mb-4">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                            <div class="relative">
                                <input type="text" id="search" name="search" value="{{ request('search') }}"
                                    placeholder="Nhập từ khóa..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 pl-10">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Danh mục</label>
                            <select id="category" name="category_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                <option value="">Tất cả danh mục</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                </option> @endforeach

                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="distance" class="block text-sm font-medium text-gray-700 mb-1">Khoảng
                                cách</label>
                                <select id="distance" name="distance"
    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
    <option value="" {{ request('distance') == '' ? 'selected' : '' }}>Không giới hạn</option>
    <option value="1" {{ request('distance') == 1 ? 'selected' : '' }}>1 km</option>
    <option value="3" {{ request('distance') == 3 ? 'selected' : '' }}>3 km</option>
    <option value="10" {{ request('distance') == 5 ? 'selected' : '' }}>10 km</option>
    <option value="10" {{ request('distance') == 10 ? 'selected' : '' }}>10 km</option>
    <option value="20" {{ request('distance') == 20 ? 'selected' : '' }}>20 km</option>
</select>
                        </div>

                        <div class="mb-4">
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sắp xếp theo</label>
                         <select id="sort" name="sort"
    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
    <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
    <option value="distance" {{ request('sort') == 'distance' ? 'selected' : '' }}>Gần nhất</option>
</select>
                        </div>

                        <div class="mb-4">
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                            <h3 class="font-semibold mb-2">Vị trí hiện tại</h3>
                            <p class="text-gray-600 text-sm mb-3" id="currentLocation">Đang xác định vị trí...</p>
                            <button type="button" id="useMyLocation"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md flex items-center justify-center">
                                <i class="fas fa-location-arrow mr-2"></i> Sử dụng vị trí của tôi
                            </button>
                        </div>

                        <div class="flex justify-between">
                            <button type="reset" class="px-4 py-2 text-gray-700 hover:text-gray-900" data-reset-url="{{ route('item.index') }}">
                                <i class="fas fa-redo mr-1"></i> Đặt lại
                            </button>
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md">
                                <i class="fas fa-search mr-1"></i> Tìm kiếm
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="w-full md:w-3/4">

                <div class="flex flex-wrap gap-2 mb-6">
                    @foreach ($categories as $category)
                        <button
                            class="category-btn px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium flex items-center"
                            data-category="{{ $category->id }}">
                            <span class="mr-1">
                                <i class="fas fa-{{ $category->icon }} mr-1"></i>
                            </span> {{ $category->name }}
                        </button>
                    @endforeach

                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="p-3 bg-green-600 text-white flex justify-between items-center">
                        <h2 class="text-lg font-semibold">Bản đồ</h2>
                        <div class="text-sm">
                            <span id="itemCount">{{ $items->count() }}</span> món đồ được tìm thấy
                        </div>
                    </div>
                    <div id="explore-map" class="h-[400px]" style="z-index:1;"></div>
                    </div>

                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Kết quả tìm kiếm <span id="resultCount"
                                class="text-gray-500">({{ $items->count() }} kết quả)</span></h2>
                    </div>

                    <!-- Danh sách item -->
                    @if ($itemsWithPaginate->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="gridView">
                            @foreach ($itemsWithPaginate as $item)
                                <div onclick="window.location='{{ route('item.detail', ['id' => $item->id]) }}'"
                                    class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300 cursor-pointer">
                                    <img src="{{ optional($item->images->first())->image_url}}" alt="Hình ảnh"
                                        class="w-full h-40 object-cover">
                                    <div class="p-4">
                                        <span
                                            class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded mb-2">
                                            {{ $item->category->name ?? 'Không có danh mục' }}
                                        </span>
                                        <h3 class="text-lg font-semibold mb-2">{{ $item->title }}</h3>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-500 text-sm flex items-center location-text"
                                                data-location="{{ $item->location }}">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                <span>Đang tải...</span>
                                            </span>
                                            <span class="text-gray-500 text-sm whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    @else
                        <!-- Hiển thị khi không có kết quả -->
                        <div class="text-center py-10">
                            <p class="text-gray-500 text-lg">Không có kết quả nào phù hợp.</p>
                            <p class="text-gray-400">Hãy thử thay đổi bộ lọc hoặc tìm kiếm từ khóa khác.</p>
                        </div>
                    @endif


                    <div class="mt-8 flex justify-center">
                        <nav class="inline-flex rounded-md shadow">
                            @if ($itemsWithPaginate->onFirstPage())
                                <span
                                    class="px-3 py-2 rounded-l-md border border-gray-300 bg-white text-gray-500 flex items-center justify-center">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            @else
                                <a href="{{ $itemsWithPaginate->previousPageUrl() }}"
                                    class="px-3 py-2 rounded-l-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 flex items-center justify-center">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            @endif

                            @php
                                $currentPage = $itemsWithPaginate->currentPage();
                                $lastPage = $itemsWithPaginate->lastPage();
                                $start = max(1, $currentPage - 1);
                                $end = min($lastPage, $currentPage + 1);
                            @endphp

                            @if ($start > 1)
                                <a href="{{ $itemsWithPaginate->url(1) }}"
                                    class="px-3 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">1</a>
                                @if ($start > 2)
                                    <span class="px-3 py-2 text-gray-500">...</span>
                                @endif
                            @endif

                            @for ($page = $start; $page <= $end; $page++)
                                <a href="{{ $itemsWithPaginate->url($page) }}"
                                    class="px-3 py-2 border border-gray-300 
                                                  {{ $page == $currentPage ? 'bg-green-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                                    {{ $page }}
                                </a>
                            @endfor

                            @if ($end < $lastPage)
                                @if ($end < $lastPage - 1)
                                    <span class="px-3 py-2 text-gray-500">...</span>
                                @endif
                                <a href="{{ $itemsWithPaginate->url($lastPage) }}"
                                    class="px-3 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">{{ $lastPage }}</a>
                            @endif

                            @if ($itemsWithPaginate->hasMorePages())
                                <a href="{{ $itemsWithPaginate->nextPageUrl() }}"
                                    class="px-3 py-2 rounded-r-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 flex items-center justify-center">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <span
                                    class="px-3 py-2 rounded-r-md border border-gray-300 bg-white text-gray-500 flex items-center justify-center">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            @endif
                        </nav>
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
<script>
    // Pass items data to JavaScript
    window.exploreItems = @json($items);
    console.log('Explore items data:', window.exploreItems);
</script>
<script>
    
</script>
@endpush
