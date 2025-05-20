<!-- resources/views/auth/custom-password-reset-form.blade.php -->
@extends('layouts.app')

@section('content')
<main class="flex-grow flex items-center justify-center py-12 px-4">
  <div class="max-w-md w-full bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="bg-green-600 py-4 px-6">
      <h2 class="text-2xl font-bold text-white text-center">Đặt lại mật khẩu</h2>
    </div>

    <div class="py-8 px-6">
      <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <!-- Email -->
        <div class="mb-6">
          <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-envelope text-gray-400"></i>
            </div>
            <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required
              class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror"
              placeholder="Nhập email của bạn">
          </div>
          @error('email')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- New Password -->
        <div class="mb-6">
          <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Mật khẩu mới</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-lock text-gray-400"></i>
            </div>
            <input id="password" type="password" name="password" required
              class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('password') border-red-500 @enderror"
              placeholder="Nhập mật khẩu mới">
          </div>
          @error('password')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
          <label for="password-confirm" class="block text-gray-700 text-sm font-medium mb-2">Xác nhận mật khẩu</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-lock text-gray-400"></i>
            </div>
            <input id="password-confirm" type="password" name="password_confirmation" required
              class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
              placeholder="Xác nhận mật khẩu mới">
          </div>
        </div>

        <!-- Submit -->
        <div class="mb-6">
          <button type="submit"
            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            Đặt lại mật khẩu
          </button>
        </div>

        <div class="text-center">
          <a href="{{ route('login') }}" class="text-sm text-green-600 hover:text-green-800 font-medium">
            Quay lại đăng nhập
          </a>
        </div>
      </form>
    </div>
  </div>
</main>
@endsection
