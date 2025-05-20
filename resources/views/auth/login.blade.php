<!-- resources/views/auth/custom-login.blade.php -->
@extends('layouts.app')

@section('content')
<main class="flex-grow flex items-center justify-center py-12 px-4">
  <div class="max-w-md w-full bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="bg-green-600 py-4 px-6">
      <h2 class="text-2xl font-bold text-white text-center">Đăng nhập</h2>
    </div>

    <form method="POST" action="{{ route('login') }}" class="py-8 px-6">
      @csrf

      <div class="mb-6">
        <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-envelope text-gray-400"></i>
          </div>
          <input type="email" id="email" name="email" value="{{ old('email') }}" required
            class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror"
            placeholder="Nhập email của bạn">
        </div>
        @error('email')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div class="mb-6">
        <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Mật khẩu</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-lock text-gray-400"></i>
          </div>
          <input type="password" id="password" name="password" required
            class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('password') border-red-500 @enderror"
            placeholder="Nhập mật khẩu">

        </div>
        @error('password')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
          <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}
            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
          <label for="remember" class="ml-2 text-sm text-gray-700">Ghi nhớ đăng nhập</label>
        </div>
        <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:text-green-800">Quên mật khẩu?</a>
      </div>

      <div class="mb-6">
        <button type="submit"
          class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 flex items-center justify-center">
          <span id="loginButtonText">Đăng nhập</span>
          <span id="loginSpinner" class="hidden ml-2">
            <i class="fas fa-spinner fa-spin"></i>
          </span>
        </button>
      </div>

      <div class="text-center">
        <p class="text-sm text-gray-600">
          Chưa có tài khoản? <a href="{{ route('register') }}" class="text-green-600 hover:text-green-800 font-medium">Đăng ký ngay</a>
        </p>
      </div>
    </form>

    <div class="bg-gray-50 py-4 px-6">
      <div class="text-center mb-4">
        <span class="text-gray-500 text-sm">Hoặc đăng nhập với</span>
      </div>
      <div class="flex justify-center space-x-4">
        <a href="{{ url('/auth/facebook') }}"
          class="flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white rounded-md px-4 py-2 w-full">
          <i class="fab fa-facebook-f mr-2"></i> Facebook
        </a>
        <a href="{{ url('/auth/google') }}"
          class="flex items-center justify-center bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-md px-4 py-2 w-full">
          <i class="fab fa-google mr-2 text-red-500"></i> Google
        </a>
      </div>
    </div>
  </div>
</main>
@endsection
