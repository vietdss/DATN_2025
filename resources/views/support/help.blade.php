@extends('layouts.app')

@section('content')

    <main class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-center mb-8 text-green-700">Trung tâm trợ giúp</h1>
            

            <!-- Danh mục trợ giúp phổ biến -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="#account" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition flex flex-col items-center text-center">
                    <div class="bg-green-100 text-green-600 p-3 rounded-full mb-4">
                        <i class="fas fa-user text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Tài khoản</h3>
                    <p class="text-gray-600">Quản lý tài khoản, đăng nhập và bảo mật</p>
                </a>
                <a href="#posting" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition flex flex-col items-center text-center">
                    <div class="bg-green-100 text-green-600 p-3 rounded-full mb-4">
                        <i class="fas fa-upload text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Đăng bài</h3>
                    <p class="text-gray-600">Hướng dẫn đăng bài, chỉnh sửa và quản lý</p>
                </a>
                <a href="#transactions" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition flex flex-col items-center text-center">
                    <div class="bg-green-100 text-green-600 p-3 rounded-full mb-4">
                        <i class="fas fa-exchange-alt text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Giao dịch</h3>
                    <p class="text-gray-600">Quy trình trao đổi, nhận và xác nhận</p>
                </a>
            </div>

            <!-- Nội dung trợ giúp -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <section id="account" class="mb-10">
                    <h2 class="text-2xl font-semibold text-green-600 mb-6 pb-2 border-b border-gray-200">Tài khoản & Bảo mật</h2>
                    
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-xl font-medium mb-3">Làm thế nào để tạo tài khoản?</h3>
                            <div class="text-gray-700">
                                <p class="mb-3">Để tạo tài khoản trên ShareCycle, hãy làm theo các bước sau:</p>
                                <ol class="list-decimal pl-6 space-y-2">
                                    <li>Truy cập trang chủ ShareCycle và nhấp vào nút "Đăng ký"</li>
                                    <li>Điền thông tin cá nhân của bạn vào biểu mẫu đăng ký</li>
                                    <li>Xác minh địa chỉ email của bạn bằng cách nhấp vào liên kết trong email xác nhận</li>
                                    <li>Hoàn thiện hồ sơ của bạn bằng cách thêm ảnh đại diện và thông tin bổ sung</li>
                                </ol>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-xl font-medium mb-3">Làm thế nào để thay đổi mật khẩu?</h3>
                            <div class="text-gray-700">
                                <p class="mb-3">Để thay đổi mật khẩu của bạn:</p>
                                <ol class="list-decimal pl-6 space-y-2">
                                    <li>Đăng nhập vào tài khoản của bạn</li>
                                    <li>Truy cập "Cài đặt tài khoản" từ menu thả xuống ở góc trên bên phải</li>
                                    <li>Chọn tab "Bảo mật"</li>
                                    <li>Nhập mật khẩu hiện tại và mật khẩu mới của bạn</li>
                                    <li>Nhấp vào "Lưu thay đổi"</li>
                                </ol>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-xl font-medium mb-3">Tôi quên mật khẩu, phải làm sao?</h3>
                            <div class="text-gray-700">
                                <p class="mb-3">Nếu bạn quên mật khẩu:</p>
                                <ol class="list-decimal pl-6 space-y-2">
                                    <li>Truy cập trang đăng nhập</li>
                                    <li>Nhấp vào liên kết "Quên mật khẩu"</li>
                                    <li>Nhập địa chỉ email đã đăng ký của bạn</li>
                                    <li>Kiểm tra email của bạn và làm theo hướng dẫn để đặt lại mật khẩu</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="posting" class="mb-10">
                    <h2 class="text-2xl font-semibold text-green-600 mb-6 pb-2 border-b border-gray-200">Đăng bài & Quản lý</h2>
                    
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-xl font-medium mb-3">Làm thế nào để đăng bài chia sẻ thực phẩm?</h3>
                            <div class="text-gray-700">
                                <p class="mb-3">Để đăng bài chia sẻ thực phẩm hoặc đồ dùng:</p>
                                <ol class="list-decimal pl-6 space-y-2">
                                    <li>Đăng nhập vào tài khoản của bạn</li>
                                    <li>Nhấp vào nút "Đăng bài" trên thanh điều hướng</li>
                                    <li>Điền thông tin chi tiết về món đồ bạn muốn chia sẻ (tên, mô tả, số lượng, v.v.)</li>
                                    <li>Tải lên ít nhất một hình ảnh của món đồ</li>
                                    <li>Chọn vị trí của bạn hoặc nơi có thể nhận món đồ</li>
                                    <li>Nhấp vào "Đăng bài"</li>
                                </ol>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-xl font-medium mb-3">Làm thế nào để chỉnh sửa hoặc xóa bài đăng?</h3>
                            <div class="text-gray-700">
                                <p class="mb-3">Để chỉnh sửa hoặc xóa bài đăng của bạn:</p>
                                <ol class="list-decimal pl-6 space-y-2">
                                    <li>Truy cập trang hồ sơ của bạn</li>
                                    <li>Tìm bài đăng bạn muốn chỉnh sửa hoặc xóa</li>
                                    <li>Nhấp vào biểu tượng "..." ở góc bài đăng</li>
                                    <li>Chọn "Chỉnh sửa" hoặc "Xóa" từ menu thả xuống</li>
                                    <li>Nếu chỉnh sửa, hãy cập nhật thông tin và nhấp "Lưu thay đổi"</li>
                                </ol>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-xl font-medium mb-3">Làm thế nào để đánh dấu món đồ đã được nhận?</h3>
                            <div class="text-gray-700">
                                <p class="mb-3">Sau khi món đồ của bạn đã được nhận:</p>
                                <ol class="list-decimal pl-6 space-y-2">
                                    <li>Truy cập trang chi tiết bài đăng</li>
                                    <li>Nhấp vào nút "Đánh dấu đã nhận"</li>
                                    <li>Chọn người dùng đã nhận món đồ từ danh sách người yêu cầu</li>
                                    <li>Xác nhận hành động</li>
                                </ol>
                                <p class="mt-3">Việc này sẽ cập nhật trạng thái bài đăng và thông báo cho người nhận.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="transactions" class="mb-10">
                    <h2 class="text-2xl font-semibold text-green-600 mb-6 pb-2 border-b border-gray-200">Giao dịch & Trao đổi</h2>
                    
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-xl font-medium mb-3">Làm thế nào để yêu cầu một món đồ?</h3>
                            <div class="text-gray-700">
                                <p class="mb-3">Để yêu cầu một món đồ từ người dùng khác:</p>
                                <ol class="list-decimal pl-6 space-y-2">
                                    <li>Tìm món đồ bạn quan tâm trên trang Khám phá hoặc Bản đồ</li>
                                    <li>Truy cập trang chi tiết của món đồ</li>
                                    <li>Nhấp vào nút "Yêu cầu"</li>
                                    <li>Viết tin nhắn ngắn giải thích lý do bạn muốn món đồ này</li>
                                    <li>Gửi yêu cầu và đợi phản hồi từ người đăng</li>
                                </ol>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-xl font-medium mb-3">Làm thế nào để liên lạc với người đăng/người nhận?</h3>
                            <div class="text-gray-700">
                                <p class="mb-3">Để liên lạc với người dùng khác:</p>
                                <ol class="list-decimal pl-6 space-y-2">
                                    <li>Truy cập trang chi tiết bài đăng hoặc hồ sơ người dùng</li>
                                    <li>Nhấp vào nút "Nhắn tin"</li>
                                    <li>Viết tin nhắn của bạn trong hộp chat</li>
                                    <li>Nhấp "Gửi"</li>
                                </ol>
                                <p class="mt-3">Bạn cũng có thể truy cập tất cả các cuộc trò chuyện của mình từ trang "Tin nhắn".</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-xl font-medium mb-3">Làm thế nào để đánh giá người dùng khác?</h3>
                            <div class="text-gray-700">
                                <p class="mb-3">Sau khi hoàn thành giao dịch, bạn có thể đánh giá người dùng khác:</p>
                                <ol class="list-decimal pl-6 space-y-2">
                                    <li>Truy cập trang "Giao dịch" trong hồ sơ của bạn</li>
                                    <li>Tìm giao dịch đã hoàn thành</li>
                                    <li>Nhấp vào "Đánh giá người dùng"</li>
                                    <li>Chọn số sao (1-5) và viết đánh giá ngắn</li>
                                    <li>Gửi đánh giá của bạn</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="safety" class="mb-10">
                    <h2 class="text-2xl font-semibold text-green-600 mb-6 pb-2 border-b border-gray-200">An toàn & Bảo mật</h2>
                    
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-xl font-medium mb-3">Làm thế nào để đảm bảo an toàn khi trao đổi?</h3>
                            <div class="text-gray-700">
                                <p class="mb-3">Để đảm bảo an toàn khi trao đổi:</p>
                                <ul class="list-disc pl-6 space-y-2">
                                    <li>Luôn gặp gỡ ở nơi công cộng, đông người</li>
                                    <li>Thông báo cho bạn bè hoặc gia đình về kế hoạch gặp gỡ của bạn</li>
                                    <li>Kiểm tra đánh giá và lịch sử của người dùng trước khi gặp</li>
                                    <li>Tin tưởng trực giác của bạn - nếu cảm thấy không an toàn, hãy hủy gặp gỡ</li>
                                    <li>Không chia sẻ thông tin cá nhân nhạy cảm như địa chỉ nhà riêng, số tài khoản ngân hàng, v.v.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-xl font-medium mb-3">Làm thế nào để báo cáo người dùng hoặc bài đăng vi phạm?</h3>
                            <div class="text-gray-700">
                                <p class="mb-3">Nếu bạn gặp người dùng hoặc bài đăng vi phạm quy tắc cộng đồng:</p>
                                <ol class="list-decimal pl-6 space-y-2">
                                    <li>Truy cập trang chi tiết bài đăng hoặc hồ sơ người dùng</li>
                                    <li>Nhấp vào biểu tượng "..." ở góc trên bên phải</li>
                                    <li>Chọn "Báo cáo"</li>
                                    <li>Chọn lý do báo cáo và cung cấp thêm chi tiết nếu cần</li>
                                    <li>Gửi báo cáo</li>
                                </ol>
                                <p class="mt-3">Đội ngũ quản trị viên sẽ xem xét báo cáo của bạn và thực hiện hành động thích hợp.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Liên hệ hỗ trợ -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold text-green-600 mb-4">Vẫn cần trợ giúp?</h2>
                <p class="mb-6">Nếu bạn không tìm thấy câu trả lời cho câu hỏi của mình, hãy liên hệ với đội ngũ hỗ trợ của chúng tôi.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="{{ route('contact') }}" class="bg-green-600 text-white py-3 px-6 rounded-lg text-center hover:bg-green-700 transition flex items-center justify-center">
                        <i class="fas fa-envelope mr-2"></i>
                        Gửi tin nhắn hỗ trợ
                    </a>
                    <a href="tel:02812345678" class="bg-gray-100 text-gray-800 py-3 px-6 rounded-lg text-center hover:bg-gray-200 transition flex items-center justify-center">
                        <i class="fas fa-phone mr-2"></i>
                        Gọi hỗ trợ: 028-1234-5678
                    </a>
                </div>
            </div>
        </div>
    </main>

    @endsection
