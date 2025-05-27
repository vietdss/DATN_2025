<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;

class TransactionStatusUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function broadcastOn()
    {
        // Channel riêng cho người nhận (receiver)
        return [
            new PrivateChannel('transactions.' . $this->transaction->receiver_id),
        ];
    }

    public function broadcastWith()
    {
        return [
            'transaction' => $this->transaction->load(['receiver', 'post', 'giver'])
        ];
    }
}
