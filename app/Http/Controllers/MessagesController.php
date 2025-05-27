<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\MessagesService;

class MessagesController extends Controller
{
    protected $service;

    public function __construct(MessagesService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $users = $this->service->getUserList();

        if ($users->count() > 0) {
            $firstUser = $users->first();
            return redirect()->route('messages.show', ['userId' => $firstUser->id]);
        }

        $lastMessages = $this->service->getLastMessages($users);

        return view('message.index', [
            'users' => $users,
            'lastMessages' => $lastMessages,
            'receiver' => null,
            'activeConversation' => null,
            'messages' => collect([])
        ]);
    }

    public function show($userId)
    {
        if (Auth::id() == $userId) {
            return redirect()->route('messages.index');
        }
        $receiver = User::findOrFail($userId);

        $users = $this->service->getUserList();
        $lastMessages = $this->service->getLastMessages($users);
        $unreadCounts = $this->service->getUnreadCounts($users);
        $messages = $this->service->getMessagesWithUser($userId);

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

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string'
        ]);

        $message = $this->service->sendMessage($request->receiver_id, $request->content);

        broadcast(new MessageSent($message));

        return response()->json(['message' => $message]);
    }

    public function markAsRead($senderId)
    {
        $remainingUnread = $this->service->markAsRead($senderId);

        return response()->json([
            'success' => true,
            'remainingUnread' => $remainingUnread
        ]);
    }

    public function getUnreadCount()
    {
        $count = $this->service->getUnreadCount();

        return response()->json(['count' => $count]);
    }
}