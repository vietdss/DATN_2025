<!-- resources/views/auth/custom-confirm-password.blade.php -->
@extends('layouts.app')

@section('content')
<main class="flex-grow flex items-center justify-center py-12 px-4">
  <div class="max-w-md w-full bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="bg-green-600 py-4 px-6">
      <h2 class="text-2xl font-bold text-white text-center">Xác nhận mật khẩu</h2>
    </div>

    <div class="py-8 px-6">
      <p class="text-gray-600 text-sm mb-6 text-center">
        Vui lòng xác nhận mật khẩu trước khi tiếp tục.
      </p>

      <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-6">
          <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Mật khẩu</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-lock text-gray-400"></i>
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
              class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('password') border-red-500 @enderror"
              placeholder="Nhập mật khẩu của bạn">
          </div>
          @error('password')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="mb-6">
          <button type="submit"
            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            Xác nhận mật khẩu
          </button>
        </div>

        @if (Route::has('password.request'))
          <div class="text-center">
            <a class="text-sm text-green-600 hover:text-green-800 font-medium" href="{{ route('password.request') }}">
              Quên mật khẩu?
            </a>
          </div>
        @endif
      </form>
    </div>
  </div>
</main>
@endsection
