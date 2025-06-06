@extends('layouts.app')
@section('title', '403 - Không có quyền truy cập')
@section('content')
<div class="flex items-center justify-center min-h-[60vh] bg-gray-50">
    <div class="text-center p-10 bg-white rounded-2xl shadow-lg max-w-md">
        <h1 class="text-4xl font-extrabold text-red-600 mb-2">404</h1>
        <p class="text-gray-700 text-lg mb-6">Trang bạn tìm kiếm không tồn tại.</p>
        <a href="{{ url('/') }}" class="inline-block px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
            Quay về trang chủ
        </a>
    </div>
</div>
@endsection
