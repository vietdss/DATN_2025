<?php

namespace App\Console\Commands;

use App\Mail\UnconfirmedTransactionWarning;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DeleteUnconfirmedTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-unconfirmed-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
{
    $cutoffDelete = now()->subDays(7);
    $cutoffWarn = now()->subDays(6)->startOfDay();

    // 1. Gửi cảnh báo trước 1 ngày
    $transactionsToWarn = Transaction::where('status', 'pending')
        ->whereDate('created_at', $cutoffWarn)
        ->get();

    foreach ($transactionsToWarn as $transaction) {
        if ($transaction->user && $transaction->user->email) {
            Mail::to($transaction->user->email)
                ->send(new UnconfirmedTransactionWarning($transaction));
        }
    }

    Transaction::whereIn('status', ['accepted', 'pending'])
        ->where('created_at', '<', $cutoffDelete)
        ->update(['status' => 'rejected']);

    $this->info('Gửi cảnh báo và chuyển trạng thái các yêu cầu cũ thành công.');
}


}
