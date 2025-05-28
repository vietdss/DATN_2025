@extends('layouts.app')

@section('content')
  <!-- Main Content -->
  <main class="container mx-auto px-4 py-8">
    <!-- Hero Section -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
      <div class="relative">
        <img src="{{ asset('images/sharecycle-twitter.jpg') }}" alt="Chia sẻ thực phẩm" class="w-full h-64 md:h-80 object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
          <h1 class="text-3xl md:text-5xl font-bold text-white text-center">Về ShareCycle</h1>
        </div>
      </div>
      <div class="p-6 md:p-8">
        <h2 class="text-2xl font-bold mb-4">Sứ mệnh của chúng tôi</h2>
        <p class="text-gray-700 mb-6">ShareCycle ra đời với sứ mệnh giảm thiểu lãng phí thực phẩm và tài nguyên, đồng thời tạo ra một cộng đồng kết nối những người có thừa với những người đang cần. Chúng tôi tin rằng mỗi hành động chia sẻ nhỏ đều có thể tạo nên sự thay đổi lớn cho xã hội và môi trường.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="bg-green-50 p-6 rounded-lg">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-leaf text-green-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-center mb-2">Bảo vệ môi trường</h3>
            <p class="text-gray-700 text-center">Giảm thiểu rác thải và lãng phí thực phẩm, góp phần bảo vệ môi trường và giảm phát thải khí nhà kính.</p>
          </div>
          <div class="bg-green-50 p-6 rounded-lg">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-hands-helping text-green-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-center mb-2">Hỗ trợ cộng đồng</h3>
            <p class="text-gray-700 text-center">Kết nối những người có thừa với những người đang cần, tạo nên một cộng đồng chia sẻ và hỗ trợ lẫn nhau.</p>
          </div>
          <div class="bg-green-50 p-6 rounded-lg">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-seedling text-green-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-center mb-2">Phát triển bền vững</h3>
            <p class="text-gray-700 text-center">Thúc đẩy lối sống bền vững và tiêu dùng có trách nhiệm, góp phần xây dựng một tương lai tốt đẹp hơn.</p>
          </div>
        </div>
        
        <h2 class="text-2xl font-bold mb-4">Câu chuyện của chúng tôi</h2>
        <p class="text-gray-700 mb-4">ShareCycle được thành lập vào năm 2025 bởi một sinh viên đại học tại Hà Nội, người đã chứng kiến lượng lớn thực phẩm bị lãng phí mỗi ngày trong các nhà hàng, siêu thị và hộ gia đình.</p>
        <p class="text-gray-700 mb-4">Ý tưởng ban đầu rất đơn giản: tạo ra một nền tảng để mọi người có thể chia sẻ thực phẩm thừa thay vì vứt bỏ. Từ đó, ShareCycle đã phát triển thành một cộng đồng lớn mạnh với hàng nghìn người dùng, không chỉ chia sẻ thực phẩm mà còn cả đồ dùng không còn nhu cầu sử dụng.</p>
        <p class="text-gray-700 mb-6">Chúng tôi tự hào đã góp phần giảm thiểu hàng tấn thực phẩm bị lãng phí và kết nối hàng nghìn người trong cộng đồng, đặc biệt là những người có hoàn cảnh khó khăn.</p>
        
 
      </div>
    </div>
    
    
    <!-- Join Us Section -->
    <div class="bg-green-600 text-white rounded-lg shadow-md overflow-hidden mb-8">
      <div class="p-6 md:p-8 text-center">
        <h2 class="text-2xl md:text-3xl font-bold mb-4">Tham gia cùng chúng tôi</h2>
        <p class="text-lg mb-6 max-w-3xl mx-auto">Hãy trở thành một phần của cộng đồng ShareCycle và cùng nhau tạo nên sự thay đổi tích cực cho xã hội và môi trường.</p>
        <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
          <a href="{{ route('register') }}" class="bg-white text-green-600 hover:bg-green-100 font-semibold py-3 px-8 rounded-lg text-lg">Đăng ký miễn phí</a>
          <a href="{{ route('contact') }}" class="bg-green-700 hover:bg-green-800 font-semibold py-3 px-8 rounded-lg text-lg">Liên hệ với chúng tôi</a>
        </div>
      </div>
    </div>
  </main>
  @endsection