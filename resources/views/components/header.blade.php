<header class="bg-green-600 text-white shadow-md sticky top-0 z-50">
  <div class="container mx-auto px-4 py-3">
    <div class="flex justify-between items-center">
      <!-- Logo -->
      <a href="{{ route('home') }}" class="text-2xl font-bold flex items-center">
        <i class="fas fa-seedling mr-2"></i>
        <span>ShareCycle</span>
      </a>

      <!-- Navigation - Desktop -->
      <nav class="hidden md:flex items-center space-x-6">
        <a href="{{ route('item.index') }}" class="hover:text-green-200">Khám phá</a>
        <a href="{{ route('user.index') }}" class="hover:text-green-200">Kết nối</a>
        <a href="{{ route('messages.index') }}" class="hover:text-green-200 relative">
          Tin nhắn
          <span id="unreadBadgeDesktop" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
        </a>
        <a href="{{ route('transactions.index') }}" class="hover:text-green-200 relative">
          Yêu cầu
          <span id="unreadTransactionBadgeDesktop" class="absolute -top-2 -right-2 bg-orange-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
        </a>
        <a href="{{ route('item.create') }}" class="hover:text-green-200">Đăng bài</a>

        <!-- User Menu -->
        <div class="relative" id="userMenuDesktop">
          <button class="flex items-center hover:text-green-200" id="userMenuButton" aria-expanded="false"
            aria-haspopup="true">
            <span>{{ Auth::check() ? Auth::user()->name : 'Tài khoản' }}</span>
            <i class="fas fa-chevron-down ml-1 text-xs"></i>
          </button>
          <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden" id="userDropdown"
            aria-labelledby="userMenuButton">
            @auth
        <a href="{{ route('user.profile', ['id' => auth()->id()]) }}"
          class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Hồ sơ</a>

        <a href="{{ route('statistics') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Thống kê</a>
        <a href="{{ route('user.setting') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Cài đặt</a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Đăng
          xuất</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Đăng nhập</a>
        <a href="{{ route('register') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Đăng ký</a>
      @endauth
          </div>
        </div>
      </nav>

      <!-- Mobile Buttons -->
      <div class="flex items-center md:hidden space-x-4">
        <a href="{{ route('item.create') }}" class="text-white p-1">
          <i class="fas fa-plus text-xl"></i>
        </a>
        <button class="text-white p-1" id="mobileMenuButton">
          <i class="fas fa-bars text-xl"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div class="md:hidden bg-green-700 hidden" id="mobileMenu">
    <div class="container mx-auto px-4 py-2">
      <nav class="flex flex-col space-y-2">
        <a href="{{ route('item.index') }}" class="block py-2 hover:text-green-200">Khám phá</a>
        <a href="{{ route('user.index') }}" class="block py-2 hover:text-green-200">Kết nối</a>
        <a href="{{ route('messages.index') }}" class="block py-2 hover:text-green-200 relative">
          Tin nhắn
          <span id="unreadBadgeMobile" class="ml-2 inline-block bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
        </a>
        <a href="{{ route('transactions.index') }}" class="block py-2 hover:text-green-200 relative">
          Yêu cầu
          <span id="unreadTransactionBadgeMobile" class="ml-2 inline-block bg-orange-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
        </a>
        <a href="{{ route('item.create') }}" class="block py-2 hover:text-green-200">Đăng bài</a>

        <!-- Mobile User Menu -->
        <div class="py-2">
          <div class="flex items-center justify-between hover:text-green-200" id="mobileAccountToggle">
            <span>Tài khoản</span>
            <i class="fas fa-chevron-down text-xs"></i>
          </div>
          <div class="pl-4 mt-2 hidden" id="mobileAccountDropdown">
            @auth
        <a href="{{ route('user.profile', ['id' => auth()->id()]) }}" class="block py-2 hover:text-green-200">Hồ
          sơ</a>
        <a href="{{ route('statistics') }}" class="block py-2 hover:text-green-200">Thống kê</a>
        <a href="{{ route('user.setting') }}" class="block py-2 hover:text-green-200">Cài đặt</a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="w-full text-left py-2 hover:text-green-200">Đăng xuất</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="block py-2 hover:text-green-200">Đăng nhập</a>
        <a href="{{ route('register') }}" class="block py-2 hover:text-green-200">Đăng ký</a>
      @endauth
          </div>
        </div>
      </nav>
    </div>
  </div>
</header>

<!-- Meta tags for JavaScript -->
@auth
<meta name="user-id" content="{{ auth()->id() }}">
@endauth
<meta name="csrf-token" content="{{ csrf_token() }}">
