<?php

namespace App\Admin\Actions\Grid;

use Encore\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use App\Mail\PostApprovedMail;

class ApproveItems extends BatchAction
{
    public $name = 'Approve Items';

    public function handle(Collection $collection)
    {
        foreach ($collection as $item) {
            $item->is_approved = true;
            $item->save();

            Mail::to($item->user->email)->send(new PostApprovedMail($item));

        }

        return $this->response()->success('Selected items have been approved.')->refresh();
    }

    public function dialog()
    {
        $this->confirm('Are you sure you want to approve the selected items?');
    }
}
