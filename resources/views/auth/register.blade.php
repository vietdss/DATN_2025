@extends('layouts.app')

@section('content')
<!-- Register Form -->
<main class="flex-grow flex items-center justify-center py-12 px-4">
  <div class="max-w-md w-full bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="bg-green-600 py-4 px-6">
      <h2 class="text-2xl font-bold text-white text-center">Đăng ký tài khoản</h2>
    </div>

    <form id="registerForm" method="POST" action="{{ route('register') }}" class="py-8 px-6">
      @csrf

      <!-- Full Name -->
      <div class="mb-4">
        <label for="fullName" class="block text-gray-700 text-sm font-medium mb-2">Họ và tên <span class="text-red-500">*</span></label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-user text-gray-400"></i>
          </div>
          <input type="text" id="fullName" name="name" value="{{ old('name') }}" required class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" placeholder="Nhập họ và tên">
        </div>
        @error('name')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Email -->
      <div class="mb-4">
        <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email <span class="text-red-500">*</span></label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-envelope text-gray-400"></i>
          </div>
          <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" placeholder="Nhập email">
        </div>
        @error('email')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Phone -->
      <div class="mb-4">
        <label for="phone" class="block text-gray-700 text-sm font-medium mb-2">Số điện thoại <span class="text-red-500">*</span></label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-phone text-gray-400"></i>
          </div>
          <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" placeholder="Nhập số điện thoại">
        </div>
        @error('phone')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Password -->
      <div class="mb-4">
        <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Mật khẩu <span class="text-red-500">*</span></label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-lock text-gray-400"></i>
          </div>
          <input type="password" id="password" name="password" required class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" placeholder="Nhập mật khẩu">
        </div>
        @error('password')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Confirm Password -->
      <div class="mb-4">
        <label for="password_confirmation" class="block text-gray-700 text-sm font-medium mb-2">Xác nhận mật khẩu <span class="text-red-500">*</span></label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-lock text-gray-400"></i>
          </div>
          <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" placeholder="Nhập lại mật khẩu">
        </div>
        @error('password_confirmation')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Terms Checkbox -->
      <div class="mb-6">
        <div class="flex items-center">
          <input type="checkbox" id="terms" name="terms" required class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
          <label for="terms" class="ml-2 text-sm text-gray-700">
            Tôi đồng ý với <a href="{{ route('terms') }}" class="text-green-600 hover:text-green-800">Điều khoản sử dụng</a> và <a href="{{ route('privacy-policy') }}" class="text-green-600 hover:text-green-800">Chính sách bảo mật</a>
          </label>
        </div>
        @error('terms')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Submit -->
      <div class="mb-6">
        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 flex items-center justify-center">
          <span id="registerButtonText">Đăng ký</span>
          <span id="registerSpinner" class="hidden ml-2">
            <i class="fas fa-spinner fa-spin"></i>
          </span>
        </button>
      </div>

      <!-- Already have an account -->
      <div class="text-center">
        <p class="text-sm text-gray-600">
          Đã có tài khoản? <a href="{{ route('login') }}" class="text-green-600 hover:text-green-800 font-medium">Đăng nhập</a>
        </p>
      </div>
    </form>

  </div>
</main>
@endsection
