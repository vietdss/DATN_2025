<?php

namespace App\Console\Commands;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Console\Command;

class DeleteExpiredProducts extends Command
{
    protected $signature = 'products:delete-expired';
    protected $description = 'Soft delete expired products and reject related transactions';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Item::where('expired_at', '<', now())
            ->chunk(100, function ($items) {
                foreach ($items as $item) {
                    // Cập nhật transaction liên quan thành 'rejected'
                    Transaction::where('post_id', $item->id)
                        ->whereIn('status', ['pending', 'accepted'])
                        ->update(['status' => 'rejected']);

                    // Xóa mềm sản phẩm
                    $item->delete();
                }
            });

        $this->info('Đã soft delete các sản phẩm hết hạn và cập nhật các transaction liên quan.');
    }
}
