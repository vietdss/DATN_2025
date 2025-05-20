@extends('layouts.app')

@section('content')

    <main class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6 mb-8">
            <h1 class="text-3xl font-bold text-center mb-8 text-green-700">Chính sách bảo mật</h1>
            
            <div class="space-y-6">
                <section>
                    <h2 class="text-2xl font-semibold text-green-600 mb-3">1. Thông tin chúng tôi thu thập</h2>
                    <p class="mb-3">Chúng tôi thu thập các loại thông tin sau đây:</p>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Thông tin cá nhân: tên, địa chỉ email, số điện thoại, địa chỉ, và thông tin hồ sơ khác mà bạn cung cấp khi đăng ký và sử dụng dịch vụ.</li>
                        <li>Thông tin vị trí: khi bạn đăng bài chia sẻ thực phẩm hoặc tìm kiếm các mục gần bạn, chúng tôi thu thập thông tin về vị trí của bạn.</li>
                        <li>Thông tin thiết bị và sử dụng: chúng tôi thu thập thông tin về thiết bị bạn sử dụng để truy cập dịch vụ và cách bạn tương tác với nền tảng.</li>
                        <li>Nội dung người dùng: bài đăng, hình ảnh, tin nhắn và các nội dung khác mà bạn tạo trên nền tảng.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-green-600 mb-3">2. Cách chúng tôi sử dụng thông tin</h2>
                    <p class="mb-3">Chúng tôi sử dụng thông tin thu thập được để:</p>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Cung cấp, duy trì và cải thiện dịch vụ của chúng tôi.</li>
                        <li>Kết nối người dùng với nhau để tạo điều kiện chia sẻ thực phẩm.</li>
                        <li>Gửi thông báo, cập nhật, và thông tin liên quan đến dịch vụ.</li>
                        <li>Phát hiện, ngăn chặn và giải quyết các vấn đề kỹ thuật, gian lận hoặc bảo mật.</li>
                        <li>Cải thiện trải nghiệm người dùng và phát triển tính năng mới.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-green-600 mb-3">3. Chia sẻ thông tin</h2>
                    <p class="mb-3">Chúng tôi có thể chia sẻ thông tin của bạn trong các trường hợp sau:</p>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Với người dùng khác: khi bạn đăng bài chia sẻ thực phẩm hoặc tương tác với người dùng khác.</li>
                        <li>Với nhà cung cấp dịch vụ: chúng tôi làm việc với các bên thứ ba để hỗ trợ dịch vụ của chúng tôi.</li>
                        <li>Theo yêu cầu pháp lý: khi luật pháp yêu cầu hoặc để bảo vệ quyền, tài sản hoặc an toàn.</li>
                        <li>Với sự đồng ý của bạn: trong các trường hợp khác khi bạn đã đồng ý.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-green-600 mb-3">4. Bảo mật thông tin</h2>
                    <p>Chúng tôi thực hiện các biện pháp bảo mật hợp lý để bảo vệ thông tin cá nhân của bạn khỏi mất mát, truy cập trái phép, sử dụng sai mục đích, thay đổi hoặc tiết lộ. Tuy nhiên, không có phương thức truyền tải qua internet hoặc lưu trữ điện tử nào là an toàn 100%. Do đó, mặc dù chúng tôi nỗ lực bảo vệ thông tin cá nhân của bạn, chúng tôi không thể đảm bảo an ninh tuyệt đối.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-green-600 mb-3">5. Quyền của bạn</h2>
                    <p class="mb-3">Tùy thuộc vào luật pháp địa phương, bạn có thể có các quyền sau:</p>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Truy cập và nhận bản sao thông tin cá nhân của bạn.</li>
                        <li>Chỉnh sửa hoặc cập nhật thông tin cá nhân không chính xác.</li>
                        <li>Hạn chế hoặc phản đối việc xử lý thông tin cá nhân của bạn.</li>
                        <li>Yêu cầu xóa thông tin cá nhân của bạn.</li>
                        <li>Nhận thông tin cá nhân của bạn ở định dạng có cấu trúc, thông dụng.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-green-600 mb-3">6. Thay đổi chính sách</h2>
                    <p>Chúng tôi có thể cập nhật Chính sách Bảo mật này theo thời gian. Chúng tôi sẽ thông báo cho bạn về những thay đổi quan trọng bằng cách đăng thông báo trên trang web của chúng tôi hoặc gửi email thông báo. Chúng tôi khuyến khích bạn xem xét Chính sách Bảo mật này định kỳ để biết thông tin mới nhất về cách chúng tôi bảo vệ thông tin của bạn.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-green-600 mb-3">7. Liên hệ với chúng tôi</h2>
                    <p>Nếu bạn có bất kỳ câu hỏi hoặc quan ngại nào về Chính sách Bảo mật này hoặc việc xử lý thông tin cá nhân của bạn, vui lòng liên hệ với chúng tôi qua:</p>
                    <div class="mt-3">
                        <p><strong>Email:</strong> privacy@foodshare.vn</p>
                        <p><strong>Địa chỉ:</strong> 123 Đường ABC, Quận XYZ, Thành phố HCM, Việt Nam</p>
                        <p><strong>Điện thoại:</strong> 028-1234-5678</p>
                    </div>
                </section>
            </div>

            <div class="mt-8 text-center text-gray-600">
                <p>Cập nhật lần cuối: Ngày 08 tháng 05 năm 2024</p>
            </div>
        </div>
    </main>
    @endsection
