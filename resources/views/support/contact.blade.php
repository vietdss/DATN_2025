@extends('layouts.app')

@section('content')

  <!-- Main Content -->
  <main class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Liên hệ với chúng tôi</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- Contact Form -->
    <div class="md:col-span-2">
      <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="p-6">
      @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        <h2 class="text-xl font-bold mb-4">Gửi tin nhắn cho chúng tôi</h2>
        <form id="contactForm" action="{{ route('contact.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
          <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Họ và tên <span
            class="text-red-500">*</span></label>
          <input type="text" id="name" name="name" required
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
          </div>
          <div>
          <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email <span
            class="text-red-500">*</span></label>
          <input type="email" id="email" name="email" required
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
          </div>
        </div>

        <div class="mb-4">
          <label for="subject" class="block text-gray-700 text-sm font-medium mb-2">Tiêu đề <span
            class="text-red-500">*</span></label>
          <input type="text" id="subject" name="subject" required
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
        </div>

        <div class="mb-4">
          <label for="message" class="block text-gray-700 text-sm font-medium mb-2">Nội dung <span
            class="text-red-500">*</span></label>
          <textarea id="message" name="message" rows="6" required
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"></textarea>
        </div>

        <div class="mb-6">
          <div class="flex items-center">
          <input type="checkbox" id="consent" name="consent" required
            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
          <label for="consent" class="ml-2 text-sm text-gray-700">
            Tôi đồng ý với <a href="{{ route('privacy-policy') }}" class="text-green-600 hover:text-green-800">Chính sách bảo
            mật</a> và cho phép ShareCycle liên hệ với tôi qua email.
          </label>
          </div>
        </div>

        <div>
          <button type="submit"
          class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
          Gửi tin nhắn
          </button>
        </div>
        </form>
      </div>
      </div>
    </div>

    <!-- Contact Info -->
    <div class="md:col-span-1">
      <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
      <div class="p-6">
        <h2 class="text-xl font-bold mb-4">Thông tin liên hệ</h2>
        <div class="space-y-4">
        <div class="flex items-start">
          <div class="flex-shrink-0 mt-1">
          <i class="fas fa-map-marker-alt text-green-600 w-5 text-center"></i>
          </div>
          <div class="ml-3">
          <h3 class="font-semibold">Địa chỉ</h3>
          <p class="text-gray-600">123 Nguyễn Huệ, Quận 1, TP.HCM, Việt Nam</p>
          </div>
        </div>

        <div class="flex items-start">
          <div class="flex-shrink-0 mt-1">
          <i class="fas fa-envelope text-green-600 w-5 text-center"></i>
          </div>
          <div class="ml-3">
          <h3 class="font-semibold">Email</h3>
          <p class="text-gray-600">contact@sharcycle.com</p>
          </div>
        </div>

        <div class="flex items-start">
          <div class="flex-shrink-0 mt-1">
          <i class="fas fa-phone-alt text-green-600 w-5 text-center"></i>
          </div>
          <div class="ml-3">
          <h3 class="font-semibold">Điện thoại</h3>
          <p class="text-gray-600">(84) 123 456 789</p>
          </div>
        </div>

        <div class="flex items-start">
          <div class="flex-shrink-0 mt-1">
          <i class="fas fa-clock text-green-600 w-5 text-center"></i>
          </div>
          <div class="ml-3">
          <h3 class="font-semibold">Giờ làm việc</h3>
          <p class="text-gray-600">Thứ Hai - Thứ Sáu: 9:00 - 17:00</p>
          <p class="text-gray-600">Thứ Bảy: 9:00 - 12:00</p>
          <p class="text-gray-600">Chủ Nhật: Đóng cửa</p>
          </div>
        </div>
        </div>
      </div>
      </div>

      <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
      <div class="p-6">
        <h2 class="text-xl font-bold mb-4">Kết nối với chúng tôi</h2>
        <div class="flex space-x-4">
        <a href="#"
          class="bg-blue-600 hover:bg-blue-700 text-white w-10 h-10 rounded-full flex items-center justify-center">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="#"
          class="bg-blue-400 hover:bg-blue-500 text-white w-10 h-10 rounded-full flex items-center justify-center">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="#"
          class="bg-pink-600 hover:bg-pink-700 text-white w-10 h-10 rounded-full flex items-center justify-center">
          <i class="fab fa-instagram"></i>
        </a>
        <a href="#"
          class="bg-red-600 hover:bg-red-700 text-white w-10 h-10 rounded-full flex items-center justify-center">
          <i class="fab fa-youtube"></i>
        </a>
        <a href="#"
          class="bg-blue-700 hover:bg-blue-800 text-white w-10 h-10 rounded-full flex items-center justify-center">
          <i class="fab fa-linkedin-in"></i>
        </a>
        </div>
      </div>
      </div>

      <div class="bg-green-50 rounded-lg shadow-md overflow-hidden">
      <div class="p-6">
        <h2 class="text-xl font-bold mb-4">Bạn cần hỗ trợ?</h2>
        <p class="text-gray-700 mb-4">Nếu bạn có câu hỏi hoặc cần hỗ trợ, hãy xem qua các câu hỏi thường gặp của chúng
        tôi.</p>
        <a href="{{ route('faq') }}"
        class="inline-block bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md">
        Xem FAQ
        </a>
      </div>
      </div>
    </div>
    </div>

    <!-- Map Section -->
    <!-- Map Section -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mt-8">
    <div class="p-6">
      <h2 class="text-xl font-bold mb-4">Vị trí của chúng tôi</h2>
      <div class="h-96 bg-gray-200 rounded-lg overflow-hidden">
      <iframe width="100%" height="100%" frameborder="0" style="border:0" loading="lazy" allowfullscreen
        referrerpolicy="no-referrer-when-downgrade"
        src="https://www.google.com/maps?q=10.7769,106.7009&hl=vi&z=15&output=embed">
      </iframe>
      </div>
    </div>
    </div>


  </main>

@endsection