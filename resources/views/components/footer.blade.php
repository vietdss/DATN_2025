<footer class="bg-gray-800 text-white py-8 mt-auto">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4">ShareCycle</h3>
                <p class="text-gray-400">Nền tảng chia sẻ thực phẩm và đồ dùng, kết nối cộng đồng và giảm lãng phí.</p>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Liên kết</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white">Trang chủ</a></li>
                    <li><a href="{{ route('item.index') }}" class="text-gray-400 hover:text-white">Khám phá</a></li>
                    <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white">Giới thiệu</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white">Liên hệ</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Hỗ trợ</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('faq') }}" class="text-gray-400 hover:text-white">FAQ</a></li>
                    <li><a href="{{ route('terms') }}" class="text-gray-400 hover:text-white">Điều khoản sử dụng</a></li>
                    <li><a href="{{ route('privacy-policy') }}" class="text-gray-400 hover:text-white">Chính sách bảo mật</a></li>
                    <li><a href="{{ route('help') }}" class="text-gray-400 hover:text-white">Trợ giúp</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Kết nối với chúng tôi</h4>
                <div class="flex space-x-4 mb-4">
                    <a href="#" class="text-gray-400 hover:text-white text-xl"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white text-xl"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white text-xl"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white text-xl"><i class="fab fa-youtube"></i></a>
                </div>
                <p class="text-gray-400">Email: contact@sharecycle.com</p>
                <p class="text-gray-400">Điện thoại: (84) 123 456 789</p>
            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400">
            <p>&copy; 2025 ShareCycle. Tất cả quyền được bảo lưu.</p>
        </div>
    </div>
</footer>
