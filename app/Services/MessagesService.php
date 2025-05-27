<?php

namespace App\Services;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessagesService
{
    public function getUserList()
    {
        $userIds = Message::where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->select('sender_id', 'receiver_id')
            ->get()
            ->map(function ($message) {
                return $message->sender_id == Auth::id() ? $message->receiver_id : $message->sender_id;
            })
            ->unique();

        return User::whereIn('id', $userIds)->get();
    }

    public function getLastMessages($users)
    {
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
        return $lastMessages;
    }

    public function getUnreadCounts($users)
    {
        $unreadCounts = [];
        foreach ($users as $user) {
            $unreadCounts[$user->id] = Message::where('sender_id', $user->id)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();
        }
        return $unreadCounts;
    }

    public function getMessagesWithUser($userId)
    {
        return Message::where(function ($query) use ($userId) {
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
    }

    public function sendMessage($receiverId, $content)
    {
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiverId,
            'content' => $content,
        ]);
        $message->load('sender');
        return $message;
    }

    public function markAsRead($senderId)
    {
        Message::where('sender_id', $senderId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();
    }

    public function getUnreadCount()
    {
        return Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();
    }
}