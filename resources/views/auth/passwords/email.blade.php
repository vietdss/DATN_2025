<!-- resources/views/auth/custom-password-reset.blade.php -->
@extends('layouts.app')

@section('content')
<main class="flex-grow flex items-center justify-center py-12 px-4">
  <div class="max-w-md w-full bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="bg-green-600 py-4 px-6">
      <h2 class="text-2xl font-bold text-white text-center">Đặt lại mật khẩu</h2>
    </div>

    <div class="py-8 px-6">
      @if (session('status'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
          {{ session('status') }}
        </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
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
          <button type="submit"
            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            Gửi liên kết đặt lại mật khẩu
          </button>
        </div>

        <div class="text-center">
          <p class="text-sm text-gray-600">
            <a href="{{ route('login') }}" class="text-green-600 hover:text-green-800 font-medium">Quay lại đăng nhập</a>
          </p>
        </div>
      </form>
    </div>
  </div>
</main>
@endsection
