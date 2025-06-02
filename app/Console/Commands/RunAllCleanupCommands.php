<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunAllCleanupCommands extends Command
{
    protected $signature = 'app:run-all-cleanups';
    protected $description = 'Run all cleanup commands in one go';

    public function handle()
    {
        // Gọi command xóa giao dịch chưa xác nhận
        $this->call('app:delete-unconfirmed-transactions');

        // Gọi command xóa sản phẩm hết hạn
        $this->call('products:delete-expired');

        $this->info('All cleanup commands executed.');
    }
}
