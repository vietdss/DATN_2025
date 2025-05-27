<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;

class TransactionRequestSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function broadcastOn()
    {
        // Channel riêng cho người cho (giver)
        return [
            new PrivateChannel('transactions.' . $this->transaction->giver_id),
        ];
    }

    public function broadcastWith()
    {
        return [
            'transaction' => $this->transaction->load(['receiver', 'post', 'giver'])
        ];
    }
}
