<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessagesController extends Controller
{
    /**
     * Hiển thị trang tin nhắn
     */
   
public function index()
{
    // Lấy danh sách người dùng đã có tin nhắn với người dùng hiện tại
    $userIds = Message::where('sender_id', Auth::id())
        ->orWhere('receiver_id', Auth::id())
        ->select('sender_id', 'receiver_id')
        ->get()
        ->map(function ($message) {
            return $message->sender_id == Auth::id() ? $message->receiver_id : $message->sender_id;
        })
        ->unique();

    $users = User::whereIn('id', $userIds)->get();

    // Nếu có user thì chuyển hướng sang cuộc trò chuyện đầu tiên
    if ($users->count() > 0) {
        $firstUser = $users->first();
        return redirect()->route('messages.show', ['userId' => $firstUser->id]);
    }

    // Nếu không có thì hiển thị giao diện mặc định
    $lastMessages = [];
    foreach ($users as $user) {
        $lastMessages[$user->id] = Message::where(function ($query) use ($user) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $user->id);
        })
            ->orWhere(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', Auth::id());
            })
            ->latest()
            ->first();
    }

    return view('message.index', [
        'users' => $users,
        'lastMessages' => $lastMessages,
        'receiver' => null,
        'activeConversation' => null,
        'messages' => collect([])
    ]);
}

    /**
     * Hiển thị tin nhắn với một người dùng cụ thể
     */
    public function show($userId)
    {
        if (Auth::id() == $userId) {
            return redirect()->route('messages.index');
        }
        $receiver = User::findOrFail($userId);

        // Lấy danh sách người dùng đã có tin nhắn với người dùng hiện tại
        $userIds = Message::where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->select('sender_id', 'receiver_id')
            ->get()
            ->map(function ($message) {
                return $message->sender_id == Auth::id() ? $message->receiver_id : $message->sender_id;
            })
            ->unique();

        $users = User::whereIn('id', $userIds)->get();

        // Lấy tin nhắn cuối cùng cho mỗi người dùng
        $lastMessages = [];
        foreach ($users as $user) {
            $lastMessages[$user->id] = Message::where(function ($query) use ($user) {
                $query->where('sender_id', Auth::id())
                    ->where('receiver_id', $user->id);
            })
                ->orWhere(function ($query) use ($user) {
                    $query->where('sender_id', $user->id)
                        ->where('receiver_id', Auth::id());
                })
                ->latest()
                ->first();
        }

        // Get unread message counts for each user
        $unreadCounts = [];
        foreach ($users as $user) {
            $unreadCounts[$user->id] = Message::where('sender_id', $user->id)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();
        }

        // Lấy tin nhắn giữa người dùng hiện tại và người nhận
        $messages = Message::where(function ($query) use ($userId) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $userId);
        })
            ->orWhere(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->where('receiver_id', Auth::id());
            })
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        // Đánh dấu cuộc trò chuyện đang hoạt động
        $activeConversation = $receiver;

        return view('message.index', [
            'users' => $users,
            'lastMessages' => $lastMessages,
            'unreadCounts' => $unreadCounts,
            'receiver' => $receiver,
            'activeConversation' => $activeConversation,
            'messages' => $messages
        ]);
    }

    /**
     * Gửi tin nhắn mới
     */
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string'
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
        ]);

        // Tải thêm thông tin người gửi
        $message->load('sender');

        // Phát sóng sự kiện tin nhắn mới
        broadcast(new MessageSent($message));

        return response()->json(['message' => $message]);
    }

    /**
     * Mark messages as read
     */
    public function markAsRead($senderId)
    {
        // Update all unread messages from this sender to read
        Message::where('sender_id', $senderId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
    
        // Get remaining unread count after marking these as read
        $remainingUnread = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'remainingUnread' => $remainingUnread
        ]);
    }

    /**
     * Get count of unread messages
     */
    public function getUnreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
