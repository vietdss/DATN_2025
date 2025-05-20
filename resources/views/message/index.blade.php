@extends('layouts.app')

@section('content')
<!-- Main Content -->
<main class="container mx-auto px-0 md:px-4 py-0 md:py-8 flex-grow h-[calc(100vh-64px)]">
  <!-- Mobile Header - Only visible on small screens -->
  <div class="md:hidden bg-white border-b p-4 flex items-center justify-between">
    <h1 class="text-xl font-bold">Tin nhắn</h1>
    <button id="toggleConversations" class="text-gray-600">
      <i class="fas fa-bars"></i>
    </button>
  </div>
  
  <!-- Messages Container -->
  <div class="bg-white rounded-lg shadow-md overflow-hidden h-full flex flex-col">
    <div class="flex flex-col md:flex-row h-full">
      <!-- Conversations List - Hidden by default on mobile -->
      <div id="conversationsList" class="hidden md:block md:w-1/3 lg:w-1/4 border-r h-full flex flex-col">
        <!-- Search -->
        <div class="p-4 border-b">
          <div class="relative">
            <input type="text" id="searchInput" placeholder="Tìm kiếm tin nhắn..." class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 pl-10">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
          </div>
        </div>
        
        @php
          $displayUsers = $users;
          if ($receiver && !$users->contains('id', $receiver->id)) {
              $displayUsers = $users->push($receiver);
          }
        @endphp
        
        <!-- Conversations -->
        <div class="overflow-y-auto flex-grow" id="conversationsContainer">
          @php
            // Sort users by the timestamp of their last message (most recent first)
            $sortedUsers = $users->sortByDesc(function($user) use ($lastMessages) {
              return isset($lastMessages[$user->id]) ? $lastMessages[$user->id]->created_at : null;
            });
          @endphp
          
          @foreach($sortedUsers as $user)
            @php
              // Check if there are unread messages from this user
              $hasUnread = isset($unreadCounts[$user->id]) && $unreadCounts[$user->id] > 0;
              
              // Only mark as active if we're on the specific conversation page
              $isActive = $receiver && $receiver->id == $user->id && Request::is("messages/{$user->id}");
            @endphp
            <div class="p-4 border-b hover:bg-gray-50 cursor-pointer conversation-item {{ $isActive ? 'bg-green-50' : '' }} {{ $hasUnread ? 'bg-gray-100' : '' }}" 
                data-user-id="{{ $user->id }}">
              <div class="flex items-center">
                <div class="relative">
                  <img src="{{ $user->profile_image ?? '/placeholder.svg?height=50&width=50' }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full"style="height: 50px; width: 50px;">
                  <span class="absolute bottom-0 right-0 w-3 h-3 {{ $user->is_online ? 'bg-green-500' : 'bg-gray-300' }} rounded-full border-2 border-white"></span>
                </div>
                <div class="ml-4 flex-1">
                  <div class="flex justify-between items-center">
                    <h3 class="font-semibold {{ $hasUnread ? 'text-black' : 'text-gray-700' }} truncate max-w-[150px]">{{ $user->name }}</h3>
                    <span class="text-xs text-gray-500">
                      @if(isset($lastMessages[$user->id]))
                        {{ $lastMessages[$user->id]->created_at->format('H:i') }}
                      @endif
                    </span>
                  </div>  
                  <p class="text-sm {{ $hasUnread ? 'font-medium text-black' : 'font-normal text-gray-600' }} truncate max-w-[180px]">
                    @if(isset($lastMessages[$user->id]))
                      @if($lastMessages[$user->id]->sender_id == auth()->id())
                        Bạn: {{ Str::limit($lastMessages[$user->id]->content, 30) }}
                      @else
                        {{ Str::limit($user->name, 15) }}: {{ Str::limit($lastMessages[$user->id]->content, 30) }}
                      @endif
                    @else
                      Bắt đầu cuộc trò chuyện
                    @endif
                  </p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
      
      <!-- Chat Area -->
      <div id="chatArea" class="flex-grow md:w-2/3 lg:w-3/4 flex flex-col h-full">
        @if($receiver)
          <!-- Chat Header -->
          <div class="p-4 border-b flex items-center">
            <button id="backToConversations" class="md:hidden mr-2 text-gray-600">
              <i class="fas fa-arrow-left"></i>
            </button>
            <div class="relative">
              <img src="{{ $receiver->profile_image ?? '/placeholder.svg?height=50&width=50' }}" alt="{{ $receiver->name }}" class="w-10 h-10 rounded-full">
              <span class="absolute bottom-0 right-0 w-2.5 h-2.5 {{ $receiver->is_online ? 'bg-green-500' : 'bg-gray-300' }} rounded-full border-2 border-white"></span>
            </div>
            <div class="ml-3">
              <h3 class="font-semibold">{{ $receiver->name }}</h3>
              <p class="text-xs text-gray-500 chat-header-status">{{ $receiver->is_online ? 'Đang hoạt động' : 'Không hoạt động' }}</p>            </div>
            <div class="ml-auto flex space-x-2">
              <button class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-info-circle"></i>
              </button>
            </div>
          </div>
          
          <!-- Chat Messages -->
          <div class="flex-grow p-4 overflow-y-auto" id="messagesContainer">
            <!-- Date Separator -->
            <div class="text-center my-4">
              <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full">{{ now()->format('d/m/Y') }}</span>
            </div>
            
            @foreach($messages as $message)
              @if($message->sender_id === auth()->id())
                <!-- Message (Sent) -->
                <div class="flex mb-4 justify-end" id="message-{{ $message->id }}">
                  <div class="max-w-[75%]">
                    <div class="bg-green-600 text-white rounded-lg p-3 break-words">
                      <p class="whitespace-pre-wrap">{{ $message->content }}</p>
                    </div>
                    <span class="text-xs text-gray-500 mr-2 text-right block">{{ $message->created_at->format('H:i') }}</span>
                  </div>
                </div>
              @else
                <!-- Message (Received) -->
                <div class="flex mb-4" id="message-{{ $message->id }}">
                  <img src="{{ $message->sender->profile_image ?? '/placeholder.svg?height=40&width=40' }}" alt="{{ $message->sender->name }}" class="w-8 h-8 rounded-full mr-2">
                  <div class="max-w-[75%]">
                    <div class="bg-gray-100 rounded-lg p-3 break-words">
                      <p class="text-gray-800 whitespace-pre-wrap">{{ $message->content }}</p>
                    </div>
                    <span class="text-xs text-gray-500 ml-2">{{ $message->created_at->format('H:i') }}</span>
                  </div>
                </div>
              @endif
            @endforeach
          </div>
          
          <!-- Chat Input -->
          <div class="p-4 border-t">
            <form id="chatForm" class="flex items-center">
              @csrf
              <input type="hidden" id="receiverId" name="receiver_id" value="{{ $receiver->id }}">
              <input type="text" id="messageInput" name="content" placeholder="Nhập tin nhắn..." class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
              <button type="submit" id="sendButton" class="ml-2 bg-green-600 hover:bg-green-700 text-white rounded-full w-10 h-10 flex items-center justify-center">
                <i class="fas fa-paper-plane"></i>
              </button>
            </form>
          </div>
          
          @if(Request::is("messages/{$receiver->id}"))
          <script>
            // Mark messages as read when viewing a specific conversation
            document.addEventListener('DOMContentLoaded', function() {
              const senderId = {{ $receiver->id }};
              if (senderId) {
                fetch(`/messages/mark-as-read/${senderId}`, {
                  method: "POST",
                  headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                  },
                })
                .then(response => response.json())
                .then(data => {
                  if (window.notificationBadge) {
                    window.notificationBadge.update(data.remainingUnread || 0);
                  }
                })
                .catch(error => console.error("Error marking messages as read:", error));
              }
            });
          </script>
          @endif
        @else
          <!-- Empty State -->
          <div class="flex flex-col items-center justify-center h-full p-6 text-center">
            <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mb-4">
              <i class="far fa-comment-dots text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Chưa tham gia cuộc trò chuyện nào</h3>
            <p class="text-gray-500 mb-4">Chọn một cuộc trò chuyện từ danh sách hoặc bắt đầu một cuộc trò chuyện mới.</p>
            <a href="{{ route('user.index') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
              Khám phá người dùng
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
</main>
@endsection
@push('scripts')
<script>
    // Any page-specific data can go here
</script>
<!-- Add mobile navigation functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const toggleConversationsBtn = document.getElementById("toggleConversations");
  const backToConversationsBtn = document.getElementById("backToConversations");
  const conversationsList = document.getElementById("conversationsList");
  const chatArea = document.getElementById("chatArea");

  if (toggleConversationsBtn) {
    toggleConversationsBtn.addEventListener("click", function() {
      conversationsList.classList.toggle("hidden");
      chatArea.classList.toggle("hidden");
    });
  }

  if (backToConversationsBtn) {
    backToConversationsBtn.addEventListener("click", function() {
      conversationsList.classList.remove("hidden");
      chatArea.classList.add("hidden");
    });
  }

  // If on mobile and a conversation is selected, hide the conversation list
  if (window.innerWidth < 768 && document.getElementById("receiverId")) {
    conversationsList.classList.add("hidden");
    chatArea.classList.remove("hidden");
  }
});
</script>
@endpush
