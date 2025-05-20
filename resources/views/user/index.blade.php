@extends('layouts.app')

@section('content')

  <!-- Main Content -->
  <main class="container mx-auto px-4 py-8">
    <div class="mb-8">
    <h1 class="text-3xl font-bold mb-2">Tìm kiếm người dùng</h1>
    <p class="text-gray-600">Tìm kiếm và kết nối với những người dùng khác trong cộng đồng ShareCycle</p>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <form id="searchForm" class="space-y-4">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label for="searchName" class="block text-sm font-medium text-gray-700 mb-1">Tên người dùng</label>
        <div class="relative">
        <input type="text" name="searchName" placeholder="Nhập tên người dùng..."
          value="{{ request('searchName') }}" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2
          focus:ring-green-500">
        <div class="absolute left-3 top-2.5">
          <i class="fas fa-search text-gray-400"></i>
        </div>
        </div>
      </div>

      <div>
        <label for="searchActivity" class="block text-sm font-medium text-gray-700 mb-1">Hoạt động</label>
        <select id="searchActivity" name="searchActivity"
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
        <option value="">Tất cả hoạt động</option>
        <option value="active" {{ request('searchActivity') == 'active' ? 'selected' : '' }}>Hoạt động trong 7 ngày
          qua</option>
        <option value="veryactive" {{ request('searchActivity') == 'veryactive' ? 'selected' : '' }}>Hoạt động trong
          3 ngày qua</option>
        <option value="superactive" {{ request('searchActivity') == 'superactive' ? 'selected' : '' }}>Hoạt động
          trong 24 giờ qua</option>
        </select>
      </div>

      <div>
        <label for="searchSort" class="block text-sm font-medium text-gray-700 mb-1">Sắp xếp theo</label>
        <select id="searchSort" name="searchSort"
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
        <option value="newest" {{ request('searchSort') == 'newest' ? 'selected' : '' }}>Mới tham gia nhất</option>
        <option value="oldest" {{ request('searchSort') == 'oldest' ? 'selected' : '' }}>Tham gia sớm nhất</option>
        <option value="items" {{ request('searchSort') == 'items' ? 'selected' : '' }}>Số lượng đồ đã chia sẻ
        </option>
        </select>
      </div>
      </div>

      <div class="flex flex-col sm:flex-row justify-end items-center space-y-3 sm:space-y-0">
      <div class="flex space-x-3">
        <button type="button" id="resetButton" onclick="        window.location.href = window.location.pathname;
  " class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">
        Đặt lại
        </button>
        <button type="submit"
        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 flex items-center">
        <i class="fas fa-search mr-2"></i> Tìm kiếm
        </button>
      </div>
      </div>
    </form>
    </div>

    <!-- Search Results -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
      <h2 class="text-xl font-bold">Kết quả tìm kiếm</h2>
<div class="text-sm text-gray-500" id="resultCount">
    Tìm thấy {{ $users->total() }} người dùng
</div>    </div>

    <div class="divide-y divide-gray-200" id="searchResults">

      @forelse ($users as $user)
<div class="p-4 hover:bg-gray-50 transition duration-150">
  <div class="flex items-start">
    <a href="{{ route('user.profile', $user->id) }}" class="flex-shrink-0">
      <img 
        src="{{ $user->profile_image ?? asset('images/default-avatar.png') }}" 
        alt="{{ $user->name }}" 
        class="w-16 h-16 rounded-full object-cover">
    </a>
    <div class="ml-4 flex-1">
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start">
        <div>
          <a href="{{ route('user.profile', $user->id) }}" class="text-lg font-semibold hover:text-green-600">
            {{ $user->name }}
          </a>
        </div>
      </div>
      <div class="mt-2 text-sm text-gray-600">
        <p>
          {{ $user->address ?? 'Chưa cập nhật địa chỉ' }} • 
          Thành viên từ {{ $user->created_at->format('m/Y') }}
        </p>
        <p class="mt-1">Đã chia sẻ: {{ $user->items_count }} món đồ</p>
      </div>
      <div class="mt-3 flex flex-wrap gap-2">
        <a href="{{ route('user.profile', $user->id) }}"
          class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
          <i class="fas fa-user mr-1"></i> Xem hồ sơ
        </a>
        <a href="{{ route('messages.show', ['userId' => $user->id]) }}" 
          class="inline-flex items-center px-3 py-1 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
          <i class="fas fa-comment-alt mr-1"></i> Nhắn tin
        </a>
      </div>
    </div>
  </div>
</div>
    @empty
      <div class="p-4 text-gray-500">Không tìm thấy người dùng nào.</div>
    @endforelse
      <!-- Pagination -->
      <div class="mt-8 flex justify-center py-5">
      <nav class="inline-flex rounded-md shadow">
        @if ($users->onFirstPage())
      <span
      class="px-3 py-2 rounded-l-md border border-gray-300 bg-white text-gray-500 flex items-center justify-center">
      <i class="fas fa-chevron-left"></i>
      </span>
      @else
      <a href="{{ $users->previousPageUrl() }}"
      class="px-3 py-2 rounded-l-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 flex items-center justify-center">
      <i class="fas fa-chevron-left"></i>
      </a>
      @endif

        @php
      $currentPage = $users->currentPage();
      $lastPage = $users->lastPage();
      $start = max(1, $currentPage - 1);
      $end = min($lastPage, $currentPage + 1);
    @endphp

        @if ($start > 1)
        <a href="{{ $users->url(1) }}"
        class="px-3 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">1</a>
        @if ($start > 2)
      <span class="px-3 py-2 text-gray-500">...</span>
      @endif
      @endif

        @for ($page = $start; $page <= $end; $page++)
      <a href="{{ $users->url($page) }}"
      class="px-3 py-2 border border-gray-300 
                {{ $page == $currentPage ? 'bg-green-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
      {{ $page }}
      </a>
      @endfor

        @if ($end < $lastPage)
        @if ($end < $lastPage - 1)
      <span class="px-3 py-2 text-gray-500">...</span>
      @endif
        <a href="{{ $users->url($lastPage) }}"
        class="px-3 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">{{ $lastPage }}</a>
      @endif

        @if ($users->hasMorePages())
      <a href="{{ $users->nextPageUrl() }}"
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
  </main>

@endsection