@extends('layouts.app')

@section('content')

  <!-- Main Content -->
  <main class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Câu hỏi thường gặp (FAQ)</h1>
    
    <!-- Search Bar -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
      <div class="p-6">
        <div class="relative">
          <input type="text" id="faqSearch" placeholder="Tìm kiếm câu hỏi..." class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 pl-10">
          <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
        </div>
      </div>
    </div>
    
    <!-- FAQ Categories -->
    <div class="mb-8">
      <div class="flex flex-wrap gap-2">
        <button class="px-4 py-2 bg-green-600 text-white rounded-full text-sm font-medium" data-category="all">Tất cả</button>
        <button class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full text-sm font-medium" data-category="general">Chung</button>
        <button class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full text-sm font-medium" data-category="account">Tài khoản</button>
        <button class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full text-sm font-medium" data-category="posting">Đăng bài</button>
        <button class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full text-sm font-medium" data-category="sharing">Chia sẻ</button>
        <button class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full text-sm font-medium" data-category="safety">An toàn</button>
      </div>
    </div>
    
    <!-- FAQ Accordion -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
      <div class="p-6">
        <div class="space-y-4" id="faqAccordion">
          <!-- General Questions -->
          <div class="border-b pb-4" data-category="general">
            <button class="flex justify-between items-center w-full text-left font-semibold py-2 focus:outline-none faq-toggle">
              <span>ShareCycle là gì?</span>
              <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
            </button>
            <div class="faq-content hidden mt-2 pl-0">
              <p class="text-gray-700">ShareCycle là nền tảng kết nối những người có thực phẩm hoặc đồ dùng thừa với những người đang cần. Mục tiêu của chúng tôi là giảm thiểu lãng phí và tạo ra một cộng đồng chia sẻ, hỗ trợ lẫn nhau.</p>
            </div>
          </div>
          
          <div class="border-b pb-4" data-category="general">
            <button class="flex justify-between items-center w-full text-left font-semibold py-2 focus:outline-none faq-toggle">
              <span>ShareCycle hoạt động như thế nào?</span>
              <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
            </button>
            <div class="faq-content hidden mt-2 pl-0">
              <p class="text-gray-700">ShareCycle hoạt động theo 3 bước đơn giản:</p>
              <ol class="list-decimal pl-5 mt-2 space-y-1 text-gray-700">
                <li>Người dùng đăng bài về món đồ họ muốn chia sẻ, kèm theo hình ảnh, mô tả và địa điểm.</li>
                <li>Người nhận tìm kiếm và liên hệ với người đăng thông qua bình luận hoặc tin nhắn.</li>
                <li>Hai bên sắp xếp việc giao nhận và đánh dấu bài đăng đã hoàn thành sau khi chia sẻ thành công.</li>
              </ol>
            </div>
          </div>
          
          <div class="border-b pb-4" data-category="general">
            <button class="flex justify-between items-center w-full text-left font-semibold py-2 focus:outline-none faq-toggle">
              <span>Sử dụng ShareCycle có mất phí không?</span>
              <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
            </button>
            <div class="faq-content hidden mt-2 pl-0">
              <p class="text-gray-700">Không, ShareCycle hoàn toàn miễn phí cho tất cả người dùng. Chúng tôi tin rằng việc chia sẻ và giảm thiểu lãng phí nên được khuyến khích và không bị giới hạn bởi bất kỳ rào cản tài chính nào.</p>
            </div>
          </div>
          
          <!-- Account Questions -->
          <div class="border-b pb-4" data-category="account">
            <button class="flex justify-between items-center w-full text-left font-semibold py-2 focus:outline-none faq-toggle">
              <span>Làm thế nào để đăng ký tài khoản?</span>
              <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
            </button>
            <div class="faq-content hidden mt-2 pl-0">
              <p class="text-gray-700">Để đăng ký tài khoản, bạn có thể:</p>
              <ol class="list-decimal pl-5 mt-2 space-y-1 text-gray-700">
                <li>Nhấp vào nút "Đăng ký" ở góc trên bên phải trang web.</li>
                <li>Điền đầy đủ thông tin cá nhân như họ tên, email, số điện thoại và mật khẩu.</li>
                <li>Đồng ý với điều khoản sử dụng và chính sách bảo mật.</li>
                <li>Nhấp vào nút "Đăng ký" để hoàn tất.</li>
              </ol>
              <p class="text-gray-700 mt-2">Bạn cũng có thể đăng ký nhanh bằng tài khoản Facebook hoặc Google.</p>
            </div>
          </div>
          
          <div class="border-b pb-4" data-category="account">
            <button class="flex justify-between items-center w-full text-left font-semibold py-2 focus:outline-none faq-toggle">
              <span>Tôi quên mật khẩu, phải làm sao?</span>
              <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
            </button>
            <div class="faq-content hidden mt-2 pl-0">
              <p class="text-gray-700">Nếu bạn quên mật khẩu, hãy làm theo các bước sau:</p>
              <ol class="list-decimal pl-5 mt-2 space-y-1 text-gray-700">
                <li>Nhấp vào liên kết "Quên mật khẩu?" trên trang đăng nhập.</li>
                <li>Nhập địa chỉ email đã đăng ký của bạn.</li>
                <li>Kiểm tra email và làm theo hướng dẫn để đặt lại mật khẩu.</li>
              </ol>
              <p class="text-gray-700 mt-2">Nếu bạn không nhận được email, hãy kiểm tra thư mục spam hoặc liên hệ với chúng tôi để được hỗ trợ.</p>
            </div>
          </div>
          
          <!-- Posting Questions -->
          <div class="border-b pb-4" data-category="posting">
            <button class="flex justify-between items-center w-full text-left font-semibold py-2 focus:outline-none faq-toggle">
              <span>Làm thế nào để đăng bài chia sẻ?</span>
              <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
            </button>
            <div class="faq-content hidden mt-2 pl-0">
              <p class="text-gray-700">Để đăng bài chia sẻ, bạn cần:</p>
              <ol class="list-decimal pl-5 mt-2 space-y-1 text-gray-700">
                <li>Đăng nhập vào tài khoản của bạn.</li>
                <li>Nhấp vào nút "Đăng bài chia sẻ" trên trang chủ hoặc trong menu.</li>
                <li>Điền đầy đủ thông tin về món đồ bạn muốn chia sẻ: tiêu đề, danh mục, mô tả, hình ảnh, địa điểm và thông tin liên hệ.</li>
                <li>Nhấp vào nút "Đăng bài" để hoàn tất.</li>
              </ol>
              <p class="text-gray-700 mt-2">Bài đăng của bạn sẽ được hiển thị ngay lập tức trên trang Khám phá và có thể được tìm kiếm bởi những người dùng khác.</p>
            </div>
          </div>
          
          <div class="border-b pb-4" data-category="posting">
            <button class="flex justify-between items-center w-full text-left font-semibold py-2 focus:outline-none faq-toggle">
              <span>Tôi có thể đăng những loại đồ nào?</span>
              <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
            </button>
            <div class="faq-content hidden mt-2 pl-0">
              <p class="text-gray-700">Bạn có thể đăng các loại đồ sau:</p>
              <ul class="list-disc pl-5 mt-2 space-y-1 text-gray-700">
                <li>Thực phẩm còn hạn sử dụng và đảm bảo an toàn vệ sinh.</li>
                <li>Rau củ, trái cây tươi.</li>
                <li>Đồ gia dụng còn sử dụng được.</li>
                <li>Quần áo, giày dép còn tốt.</li>
                <li>Đồ điện tử còn hoạt động.</li>
                <li>Sách, đồ chơi, và các vật dụng khác còn sử dụng được.</li>
              </ul>
              <p class="text-gray-700 mt-2">Lưu ý: Chúng tôi không cho phép đăng các sản phẩm bất hợp pháp, nguy hiểm, thuốc lá, rượu bia, thuốc kê đơn, hoặc các sản phẩm vi phạm điều khoản sử dụng của chúng tôi.</p>
            </div>
          </div>
          
          <!-- Sharing Questions -->
          <div class="border-b pb-4" data-category="sharing">
            <button class="flex justify-between items-center w-full text-left font-semibold py-2 focus:outline-none faq-toggle">
              <span>Làm thế nào để nhận đồ từ người khác?</span>
              <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
            </button>
            <div class="faq-content hidden mt-2 pl-0">
              <p class="text-gray-700">Để nhận đồ từ người khác, bạn có thể:</p>
              <ol class="list-decimal pl-5 mt-2 space-y-1 text-gray-700">
                <li>Tìm kiếm món đồ bạn cần trên trang Khám phá.</li>
                <li>Khi tìm thấy món đồ phù hợp, bạn có thể bình luận trực tiếp trên bài đăng hoặc nhắn tin cho người đăng.</li>
                <li>Trao đổi với người đăng về thời gian và địa điểm giao nhận.</li>
                <li>Sau khi nhận đồ, hãy xác nhận đã nhận và đánh giá người chia sẻ.</li>
              </ol>
            </div>
          </div>
          
          <div class="border-b pb-4" data-category="sharing">
            <button class="flex justify-between items-center w-full text-left font-semibold py-2 focus:outline-none faq-toggle">
              <span>Có phải trả phí vận chuyển không?</span>
              <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
            </button>
            <div class="faq-content hidden mt-2 pl-0">
              <p class="text-gray-700">Việc trả phí vận chuyển tùy thuộc vào thỏa thuận giữa người chia sẻ và người nhận. ShareCycle không quy định hoặc can thiệp vào việc này. Tuy nhiên, chúng tôi khuyến khích:</p>
              <ul class="list-disc pl-5 mt-2 space-y-1 text-gray-700">
                <li>Người nhận có thể tự đến lấy đồ nếu ở gần.</li>
                <li>Người chia sẻ có thể giao đồ nếu thuận tiện.</li>
                <li>Hai bên có thể thỏa thuận về việc chia sẻ phí vận chuyển nếu sử dụng dịch vụ giao hàng.</li>
              </ul>
              <p class="text-gray-700 mt-2">Chúng tôi khuyến khích sự linh hoạt và thỏa thuận rõ ràng giữa các bên để tránh hiểu lầm.</p>
            </div>
          </div>
          
          <!-- Safety Questions -->
          <div class="border-b pb-4" data-category="safety">
            <button class="flex justify-between items-center w-full text-left font-semibold py-2 focus:outline-none faq-toggle">
              <span>Làm thế nào để đảm bảo an toàn khi chia sẻ thực phẩm?</span>
              <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
            </button>
            <div class="faq-content hidden mt-2 pl-0">
              <p class="text-gray-700">Để đảm bảo an toàn khi chia sẻ thực phẩm, hãy tuân thủ các nguyên tắc sau:</p>
              <ul class="list-disc pl-5 mt-2 space-y-1 text-gray-700">
                <li>Chỉ chia sẻ thực phẩm còn trong hạn sử dụng và được bảo quản đúng cách.</li>
                <li>Cung cấp thông tin đầy đủ về thực phẩm: nguồn gốc, thành phần, hạn sử dụng, và cách bảo quản.</li>
                <li>Không chia sẻ thực phẩm đã mở bao bì hoặc đã qua sử dụng một phần (trừ trường hợp như bánh kem, pizza còn nguyên miếng).</li>
                <li>Đảm bảo thực phẩm được đóng gói sạch sẽ và an toàn.</li>
                <li>Nếu bạn có bất kỳ nghi ngờ nào về độ an toàn của thực phẩm, hãy không chia sẻ.</li>
              </ul>
              <p class="text-gray-700 mt-2">Người nhận cũng nên kiểm tra kỹ thực phẩm trước khi sử dụng và không nhận những thực phẩm có dấu hiệu hư hỏng hoặc không đảm bảo vệ sinh.</p>
            </div>
          </div>
          
          <div class="border-b pb-4" data-category="safety">
            <button class="flex justify-between items-center w-full text-left font-semibold py-2 focus:outline-none faq-toggle">
              <span>Làm thế nào để đảm bảo an toàn khi gặp gỡ người lạ để trao đổi đồ?</span>
              <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
            </button>
            <div class="faq-content hidden mt-2 pl-0">
              <p class="text-gray-700">Để đảm bảo an toàn khi gặp gỡ người lạ, hãy tuân thủ các nguyên tắc sau:</p>
              <ul class="list-disc pl-5 mt-2 space-y-1 text-gray-700">
                <li>Chọn địa điểm công cộng, đông người để gặp gỡ (như quán cà phê, trung tâm thương mại, công viên).</li>
                <li>  đông người để gặp gỡ (như quán cà phê, trung tâm thương mại, công viên).</li>
                <li>Gặp gỡ vào ban ngày hoặc nơi có ánh sáng tốt.</li>
                <li>Thông báo cho bạn bè hoặc người thân về kế hoạch gặp gỡ của bạn.</li>
                <li>Kiểm tra đánh giá và lịch sử hoạt động của người dùng trước khi gặp.</li>
                <li>Tin tưởng trực giác của bạn - nếu cảm thấy không an toàn, hãy hủy cuộc gặp.</li>
              </ul>
              <p class="text-gray-700 mt-2">Nếu có thể, hãy mang theo một người bạn khi gặp gỡ người lạ, đặc biệt là lần đầu tiên.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Still Have Questions -->
    <div class="bg-green-50 rounded-lg shadow-md overflow-hidden">
      <div class="p-6 text-center">
        <h2 class="text-2xl font-bold mb-4">Vẫn còn thắc mắc?</h2>
        <p class="text-gray-700 mb-6 max-w-2xl mx-auto">Nếu bạn không tìm thấy câu trả lời cho câu hỏi của mình, đừng ngần ngại liên hệ với đội ngũ hỗ trợ của chúng tôi. Chúng tôi luôn sẵn sàng giúp đỡ bạn!</p>
        <a href="{{ route('contact') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-md">
          Liên hệ với chúng tôi
        </a>
      </div>
    </div>
  </main>


  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // FAQ Accordion
      const faqToggles = document.querySelectorAll('.faq-toggle');
      
      faqToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
          const content = this.nextElementSibling;
          const icon = this.querySelector('i');
          
          // Toggle content
          content.classList.toggle('hidden');
          
          // Rotate icon
          if (content.classList.contains('hidden')) {
            icon.style.transform = 'rotate(0deg)';
          } else {
            icon.style.transform = 'rotate(180deg)';
          }
        });
      });
      
      // Category filtering
      const categoryButtons = document.querySelectorAll('[data-category]');
      const faqItems = document.querySelectorAll('#faqAccordion > div');
      
      categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
          const category = this.getAttribute('data-category');
          
          // Update active button
          categoryButtons.forEach(btn => {
            btn.classList.remove('bg-green-600', 'text-white');
            btn.classList.add('bg-gray-200', 'text-gray-700');
          });
          this.classList.remove('bg-gray-200', 'text-gray-700');
          this.classList.add('bg-green-600', 'text-white');
          
          // Filter items
          if (category === 'all') {
            faqItems.forEach(item => {
              item.style.display = 'block';
            });
          } else {
            faqItems.forEach(item => {
              if (item.getAttribute('data-category') === category) {
                item.style.display = 'block';
              } else {
                item.style.display = 'none';
              }
            });
          }
        });
      });
      
      // Search functionality
      const searchInput = document.getElementById('faqSearch');
      
      searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        faqItems.forEach(item => {
          const question = item.querySelector('button').textContent.toLowerCase();
          const answer = item.querySelector('.faq-content').textContent.toLowerCase();
          
          if (question.includes(searchTerm) || answer.includes(searchTerm)) {
            item.style.display = 'block';
          } else {
            item.style.display = 'none';
          }
        });
      });
    });
  </script>
  @endsection
