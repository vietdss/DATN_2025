@extends('layouts.app')

@section('content')
  <!-- Hero Section -->
  <section class="bg-gradient-to-r from-green-500 to-green-600 text-white py-16 gap-x-4">
    <div class="container mx-auto px-4 flex flex-col md:flex-row items-center">
    <div class="md:w-1/2 mb-8 md:mb-0">
      <h1 class="text-4xl md:text-5xl font-bold mb-4">Chia sẻ đồ dùng và thực phẩm, giảm lãng phí</h1>
      <p class="text-xl mb-6">Kết nối những người có thực phẩm và đồ dùng không sử dụng với những người có nhu cầu. Cùng nhau giảm lãng
      phí và giúp
      đỡ cộng đồng.</p>
      <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
      <a href="{{ route('item.index') }}"
        class="bg-white text-green-600 hover:bg-green-100 font-semibold py-3 px-6 rounded-lg text-center">Tìm đồ gần
        bạn</a>
      <a href="{{ route('item.create') }}"
        class="bg-green-700 hover:bg-green-800 font-semibold py-3 px-6 rounded-lg text-center">Đăng bài chia sẻ</a>
      </div>
    </div>
    <div class="md:w-1/2">
      <img src="/placeholder.svg?height=400&width=600" alt="Chia sẻ thực phẩm" class="rounded-lg shadow-lg">
    </div>
    </div>
  </section>

  <!-- Categories Section -->
  <section class="py-12 bg-white">
    <div class="container mx-auto px-4">
    <h2 class="text-3xl font-bold text-center mb-8">Khám phá theo danh mục</h2>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
      @foreach($categories as $category)
      <a href="{{ route('item.index', ['category_id' => $category->id]) }}" class="bg-green-50 hover:bg-green-100 p-6 rounded-lg text-center transition duration-300">
      <i class="fas fa-{{ $category->icon }} text-4xl text-green-600 mb-3"></i>
      <h3 class="text-lg font-semibold">{{ $category->name }}</h3>
      </a>
    @endforeach
    </div>
    </div>
  </section>

  <!-- Recent Items Section -->
  <section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-8">
      <h2 class="text-3xl font-bold">Đồ mới đăng gần đây</h2>
      <a href="{{ route('item.index') }}" class="text-green-600 hover:text-green-700 font-medium">
      Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
      </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-6" id="recentItems">
      @foreach($items as $item)
      <div onclick="window.location='{{ route('item.detail', ['id' => $item->id]) }}'"
      class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300 cursor-pointer">
      <img src="{{ $item->images->first()->image_url}}" alt="Hình ảnh" class="w-full h-40 object-cover">
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
    @endforeach
    </div>
    </div>
  </section>


  <!-- How It Works Section -->
  <section class="py-12 bg-white">
    <div class="container mx-auto px-4">
    <h2 class="text-3xl font-bold text-center mb-12">Cách thức hoạt động</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div class="text-center">
      <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-camera text-3xl text-green-600"></i>
      </div>
      <h3 class="text-xl font-semibold mb-2">1. Đăng bài</h3>
      <p class="text-gray-600">Chụp ảnh và mô tả món đồ bạn muốn chia sẻ, thêm địa điểm và thời gian có thể nhận.</p>
      </div>
      <div class="text-center">
      <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-comments text-3xl text-green-600"></i>
      </div>
      <h3 class="text-xl font-semibold mb-2">2. Kết nối</h3>
      <p class="text-gray-600">Người nhận sẽ liên hệ với bạn thông qua tin nhắn để sắp xếp việc nhận đồ.</p>
      </div>
      <div class="text-center">
      <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-handshake text-3xl text-green-600"></i>
      </div>
      <h3 class="text-xl font-semibold mb-2">3. Chia sẻ</h3>
      <p class="text-gray-600">Gặp gỡ và trao đổi món đồ. Đánh dấu bài đăng đã hoàn thành sau khi chia sẻ thành công.
      </p>
      </div>
    </div>
    </div>
  </section>


  <!-- CTA Section -->
  <section class="py-16 bg-green-600 text-white">
    <div class="container mx-auto px-4 text-center">
    <h2 class="text-3xl md:text-4xl font-bold mb-4">Bắt đầu chia sẻ ngay hôm nay</h2>
    <p class="text-xl mb-8 max-w-3xl mx-auto">Hãy tham gia cộng đồng của chúng tôi để giảm lãng phí thực phẩm và đồ
      dùng, đồng thời giúp đỡ những người cần.</p>
    <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
      <a href="{{ route('register') }}"
      class="bg-white text-green-600 hover:bg-green-100 font-semibold py-3 px-8 rounded-lg text-lg">Đăng ký miễn
      phí</a>
      <a href="{{ route('item.index') }}" class="bg-green-700 hover:bg-green-800 font-semibold py-3 px-8 rounded-lg text-lg">Khám phá
      đồ gần bạn</a>
    </div>
    </div>
  </section>
@endsection