@extends('layouts.app')

@section('content')
  <!-- Main Content -->
  <main class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Cài đặt tài khoản</h1>

    <!-- Settings Tabs -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
    <div class="border-b">
      <nav class="flex">
      <button class="px-6 py-3 border-b-2 border-green-600 text-green-600 font-medium" id="profileTab">Hồ sơ</button>
      <button class="px-6 py-3 text-gray-500 hover:text-gray-700" id="securityTab">Bảo mật</button>
      </nav>
    </div>

    <!-- Profile Settings -->
    <div class="p-6" id="profileSettings">
      <form id="profileForm" action="{{ route('user.update.profile') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

   <!-- Profile Picture -->
<div class="mb-6">
  <h2 class="text-xl font-bold mb-4">Ảnh đại diện</h2>
  <div class="flex items-center">
    <img src="{{ $user->profile_image ?? '/placeholder.svg?height=100&width=100' }}" 
         alt="{{ $user->name }}" 
         class="w-24 h-24 rounded-full mr-6 object-cover">
    <div>
      <div class="flex space-x-3 mb-2">
        <label for="profile_image" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md cursor-pointer">
          Thay đổi ảnh
          <input type="file" id="profile_image" name="profile_image" class="hidden" accept="image/*">
        </label>
        @if($user->profile_image)
        <button type="button" id="removeProfileImage" data-action="{{ route('user.remove.profile.image') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-md">
          Xóa
        </button>
        @endif
      </div>
      <p class="text-gray-500 text-sm">Định dạng JPG, GIF hoặc PNG. Kích thước tối đa 5MB.</p>
    </div>
  </div>
</div>

      <!-- Personal Information -->
      <div class="mb-6">
        <h2 class="text-xl font-bold mb-4">Thông tin cá nhân</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="fullName" class="block text-gray-700 text-sm font-medium mb-2">Họ và tên</label>
          <input type="text" id="fullName" name="name" value="{{ $user->name }}"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
        </div>
        <div>
          <label for="username" class="block text-gray-700 text-sm font-medium mb-2">Tên người dùng</label>
          <input type="text" id="username" name="username" value="{{ $user->username ?? '' }}"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
        </div>
        <div>
          <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email</label>
          <input type="email" id="email" name="email" value="{{ $user->email }}"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
        </div>
        <div>
          <label for="phone" class="block text-gray-700 text-sm font-medium mb-2">Số điện thoại</label>
          <input type="tel" id="phone" name="phone" value="{{ $user->phone ?? '' }}"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
        </div>
        </div>
      </div>

      <!-- Address -->
      <div class="mb-6">
        <h2 class="text-xl font-bold mb-4">Địa chỉ</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label for="province" class="block text-gray-700 text-sm font-medium mb-2">Tỉnh/Thành phố</label>
          <select id="province" name="province"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
          <option value="">Chọn Tỉnh/Thành phố</option>
          <!-- Các option sẽ được thêm bằng JavaScript -->
          </select>
        </div>
        <div>
          <label for="district" class="block text-gray-700 text-sm font-medium mb-2">Quận/Huyện</label>
          <select id="district" name="district"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
          <option value="">Chọn Quận/Huyện</option>
          <!-- Các option sẽ được thêm bằng JavaScript -->
          </select>
        </div>
        <div>
          <label for="ward" class="block text-gray-700 text-sm font-medium mb-2">Xã/Phường</label>
          <select id="ward" name="ward"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
          <option value="">Chọn Xã/Phường</option>
          <!-- Các option sẽ được thêm bằng JavaScript -->
          </select>
        </div>
        </div>

        <!-- Hidden input to store the combined address -->
        <input type="hidden" id="address" name="address" value="{{ $user->address ?? '' }}">
      </div>

      <!-- Save Button -->
      <div class="flex justify-end">
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-md">
        Lưu thay đổi
        </button>
      </div>
      </form>
    </div>

    <!-- Security Settings -->
    <div class="p-6 hidden" id="securitySettings">
      <!-- Display Success Message -->
      @if(session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
      </div>
      @endif

      <!-- Display Error Messages -->
      @if($errors->any())
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <ul class="list-disc pl-5">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif
      <form id="securityForm" action="{{ route('user.update.password') }}" method="POST">
      @csrf
      @method('PUT')

      <!-- Change Password -->
      <div class="mb-6">
        <h2 class="text-xl font-bold mb-4">Thay đổi mật khẩu</h2>
        <div class="space-y-4">
        <div>
          <label for="currentPassword" class="block text-gray-700 text-sm font-medium mb-2">Mật khẩu hiện
          tại</label>
          <input type="password" id="currentPassword" name="current_password"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
        </div>
        <div>
          <label for="newPassword" class="block text-gray-700 text-sm font-medium mb-2">Mật khẩu mới</label>
          <input type="password" id="newPassword" name="new_password"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
        </div>
        <div>
          <label for="confirmPassword" class="block text-gray-700 text-sm font-medium mb-2">Xác nhận mật khẩu
          mới</label>
          <input type="password" id="confirmPassword" name="new_password_confirmation"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
        </div>
        </div>
      </div>

      <!-- Delete Account -->
      <div class="mb-6">
        <h2 class="text-xl font-bold mb-4">Xóa tài khoản</h2>
        <div class="bg-red-50 rounded-lg p-4">
        <p class="text-gray-700 mb-4">Khi bạn xóa tài khoản, tất cả dữ liệu của bạn sẽ bị xóa vĩnh viễn và không thể
          khôi phục. Vui lòng cân nhắc kỹ trước khi thực hiện hành động này.</p>
        <button type="button" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md"
          id="deleteAccountBtn" data-action="{{ route('user.delete') }}">
          Xóa tài khoản
        </button>
        </div>
      </div>

      <!-- Save Button -->
      <div class="flex justify-end">
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-md">
        Lưu thay đổi
        </button>
      </div>
      </form>
    </div>
    </div>

  <!-- Success Modal -->
  <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
      <div class="flex items-center justify-center mb-4 text-green-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
      </div>
      <h3 class="text-xl font-bold text-center mb-2" id="successModalTitle">Thành công!</h3>
      <p class="text-center text-gray-700 mb-6" id="successModalMessage">Thao tác đã được thực hiện thành công.</p>
      <div class="flex justify-center">
        <button type="button" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-md" id="successModalClose">
          Đóng
        </button>
      </div>
    </div>
  </div>

  <!-- Confirmation Modal -->
  <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
      <div class="flex items-center justify-center mb-4 text-red-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
      </div>
      <h3 class="text-xl font-bold text-center mb-2" id="confirmModalTitle">Xác nhận xóa tài khoản</h3>
      <p class="text-center text-gray-700 mb-6" id="confirmModalMessage">Khi bạn xóa tài khoản, tất cả dữ liệu của bạn sẽ bị xóa vĩnh viễn và không thể khôi phục. Bạn có chắc chắn muốn tiếp tục?</p>
      <div class="flex justify-center space-x-4">
        <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-6 rounded-md" id="confirmModalCancel">
          Hủy bỏ
        </button>
        <button type="button" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-6 rounded-md" id="confirmModalConfirm">
          Xóa tài khoản
        </button>
      </div>
    </div>
  </div>
  </main>
@endsection
