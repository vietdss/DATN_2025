@extends('layouts.app')

@section('content')

  <!-- Main Content -->
  <main class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="p-6 md:p-8">
        <h1 class="text-3xl font-bold mb-6">Điều khoản sử dụng</h1>
        <p class="text-gray-500 mb-8">Cập nhật lần cuối: 01/06/2023</p>
        
        <div class="prose max-w-none text-gray-700">
          <p>Chào mừng bạn đến với ShareCycle. Vui lòng đọc kỹ các điều khoản sử dụng này trước khi sử dụng trang web và dịch vụ của chúng tôi.</p>
          
          <h2 class="text-xl font-bold mt-6 mb-3">1. Chấp nhận điều khoản</h2>
          <p>Bằng cách truy cập hoặc sử dụng trang web ShareCycle, bạn đồng ý bị ràng buộc bởi các điều khoản và điều kiện này. Nếu bạn không đồng ý với bất kỳ phần nào của các điều khoản này, bạn không được phép sử dụng trang web của chúng tôi.</p>
          
          <h2 class="text-xl font-bold mt-6 mb-3">2. Thay đổi điều khoản</h2>
          <p>Chúng tôi có thể sửa đổi hoặc cập nhật các điều khoản này bất cứ lúc nào mà không cần thông báo trước. Việc bạn tiếp tục sử dụng trang web sau khi thay đổi đồng nghĩa với việc bạn chấp nhận các điều khoản đã được sửa đổi.</p>
          
          <h2 class="text-xl font-bold mt-6 mb-3">3. Tài khoản người dùng</h2>
          <p>Khi bạn tạo tài khoản trên ShareCycle, bạn phải cung cấp thông tin chính xác, đầy đủ và cập nhật. Bạn chịu trách nhiệm bảo mật tài khoản của mình, bao gồm mật khẩu, và bạn chịu trách nhiệm cho tất cả hoạt động diễn ra dưới tài khoản của mình.</p>
          <p>Bạn phải thông báo cho chúng tôi ngay lập tức về bất kỳ vi phạm bảo mật nào hoặc việc sử dụng trái phép tài khoản của bạn. Chúng tôi không chịu trách nhiệm cho bất kỳ tổn thất nào phát sinh từ việc người khác sử dụng tài khoản của bạn.</p>
          
          <h2 class="text-xl font-bold mt-6 mb-3">4. Nội dung người dùng</h2>
          <p>Khi bạn đăng nội dung lên ShareCycle, bạn cấp cho chúng tôi quyền không độc quyền, miễn phí, có thể chuyển nhượng, có thể cấp phép lại để sử dụng, sao chép, sửa đổi, phân phối, thực hiện và hiển thị nội dung đó trên trang web của chúng tôi.</p>
          <p>Bạn chịu trách nhiệm về nội dung bạn đăng và đảm bảo rằng nó không vi phạm quyền của bất kỳ bên thứ ba nào, không chứa thông tin sai lệch, không phù hợp hoặc bất hợp pháp.</p>
          
          <h2 class="text-xl font-bold mt-6 mb-3">5. Hành vi bị cấm</h2>
          <p>Khi sử dụng ShareCycle, bạn không được:</p>
          <ul class="list-disc pl-5 space-y-1">
            <li>Vi phạm bất kỳ luật pháp hoặc quy định nào.</li>
            <li>Đăng nội dung xúc phạm, khiêu dâm, đe dọa, quấy rối, phỉ báng, hoặc bất hợp pháp.</li>
            <li>Đăng thông tin cá nhân của người khác mà không có sự đồng ý.</li>
            <li>Đăng thông tin sai lệch hoặc gây hiểu lầm.</li>
            <li>Đăng quảng cáo hoặc spam.</li>
            <li>Cố gắng truy cập trái phép vào hệ thống của chúng tôi.</li>
            <li>Can thiệp vào hoạt động bình thường của trang web.</li>
            <li>Đăng các sản phẩm bất hợp pháp, nguy hiểm, thuốc lá, rượu bia, thuốc kê đơn, hoặc các sản phẩm vi phạm điều khoản sử dụng của chúng tôi.</li>
          </ul>
          
          <h2 class="text-xl font-bold mt-6 mb-3">6. Chia sẻ thực phẩm và đồ dùng</h2>
          <p>ShareCycle là nền tảng kết nối những người có thực phẩm hoặc đồ dùng thừa với những người đang cần. Khi chia sẻ thực phẩm, bạn phải đảm bảo:</p>
          <ul class="list-disc pl-5 space-y-1">
            <li>Thực phẩm còn trong hạn sử dụng và được bảo quản đúng cách.</li>
            <li>Cung cấp thông tin đầy đủ về thực phẩm: nguồn gốc, thành phần, hạn sử dụng, và cách bảo quản.</li>
            <li>Không chia sẻ thực phẩm đã mở bao bì hoặc đã qua sử dụng một phần (trừ trường hợp như bánh kem, pizza còn nguyên miếng).</li>
            <li>Đảm bảo thực phẩm được đóng gói sạch sẽ và an toàn.</li>
          </ul>
          <p>Chúng tôi không chịu trách nhiệm về chất lượng, an toàn hoặc tính hợp pháp của các món đồ được chia sẻ trên nền tảng của chúng tôi.</p>
          
          <h2 class="text-xl font-bold mt-6 mb-3">7. Giao dịch giữa người dùng</h2>
          <p>ShareCycle chỉ là nền tảng kết nối người dùng và không tham gia vào các giao dịch giữa người dùng. Chúng tôi không đảm bảo chất lượng, an toàn hoặc tính hợp pháp của các món đồ được chia sẻ, và không chịu trách nhiệm về bất kỳ tranh chấp nào giữa người dùng.</p>
          <p>Chúng tôi khuyến khích người dùng thực hiện các biện pháp an toàn khi gặp gỡ người lạ, như gặp ở nơi công cộng và thông báo cho người thân về kế hoạch của bạn.</p>
          
          <h2 class="text-xl font-bold mt-6 mb-3">8. Quyền sở hữu trí tuệ</h2>
          <p>Trang web và nội dung của ShareCycle, bao gồm nhưng không giới hạn ở văn bản, đồ họa, logo, biểu tượng, hình ảnh, âm thanh, phần mềm và mã nguồn, đều thuộc sở hữu của ShareCycle hoặc các bên cấp phép của chúng tôi và được bảo vệ bởi luật sở hữu trí tuệ.</p>
          <p>Bạn không được sao chép, sửa đổi, phân phối, bán, cho thuê, cấp phép, thực hiện công khai hoặc tạo ra các tác phẩm phái sinh từ bất kỳ phần nào của trang web mà không có sự đồng ý rõ ràng bằng văn bản từ chúng tôi.</p>
          
          <h2 class="text-xl font-bold mt-6 mb-3">9. Từ chối bảo đảm</h2>
          <p>Trang web và dịch vụ của chúng tôi được cung cấp "nguyên trạng" và "như có sẵn", không có bất kỳ bảo đảm nào, dù rõ ràng hay ngụ ý. Chúng tôi không đảm bảo rằng trang web sẽ luôn có sẵn, không bị gián đoạn, kịp thời, an toàn hoặc không có lỗi.</p>
          
          <h2 class="text-xl font-bold mt-6 mb-3">10. Giới hạn trách nhiệm</h2>
          <p>Trong phạm vi tối đa được pháp luật cho phép, ShareCycle và các giám đốc, nhân viên, đối tác và đại lý của chúng tôi sẽ không chịu trách nhiệm về bất kỳ thiệt hại nào, dù trực tiếp, gián tiếp, ngẫu nhiên, đặc biệt, hậu quả hoặc trừng phạt, phát sinh từ hoặc liên quan đến việc sử dụng hoặc không thể sử dụng trang web hoặc dịch vụ của chúng tôi.</p>
          
          <h2 class="text-xl font-bold mt-6 mb-3">11. Bồi thường</h2>
          <p>Bạn đồng ý bồi thường, bảo vệ và giữ cho ShareCycle và các giám đốc, nhân viên, đối tác và đại lý của chúng tôi không bị tổn hại từ bất kỳ khiếu nại, yêu cầu, trách nhiệm, thiệt hại hoặc chi phí nào, bao gồm cả phí luật sư hợp lý, phát sinh từ việc bạn vi phạm các điều khoản này, vi phạm quyền của bên thứ ba, hoặc hành vi sai trái của bạn.</p>
          
          <h2 class="text-xl font-bold mt-6 mb-3">12. Luật áp dụng</h2>
          <p>Các điều khoản này sẽ được điều chỉnh và giải thích theo luật pháp Việt Nam, không tính đến các nguyên tắc xung đột pháp luật.</p>
          
          <h2 class="text-xl font-bold mt-6 mb-3">13. Liên hệ</h2>
          <p>Nếu bạn có bất kỳ câu hỏi nào về các điều khoản này, vui lòng liên hệ với chúng tôi tại:</p>
          <p>Email: terms@sharcycle.com<br>
          Địa chỉ: 123 Nguyễn Huệ, Quận 1, TP.HCM, Việt Nam</p>
        </div>
      </div>
    </div>
  </main>

  @endsection