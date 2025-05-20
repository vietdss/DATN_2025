<?php

namespace App\Admin\Actions\Grid;

use App\Mail\PostUnapprovedMail;
use Encore\Admin\Actions\BatchAction;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class UnapproveItems extends BatchAction
{
    public $name = 'Unapprove Items';

    public function handle(Collection $collection, Request $request)
    {
        $reason = $request->get('reason');

        foreach ($collection as $item) {
            $item->is_approved = false;
            $item->save();

            Mail::to($item->user->email)->send(new PostUnapprovedMail($item, $reason));
        }

        return $this->response()->success('Selected items have been unapproved.')->refresh();
    }

    public function form()
    {
        $this->textarea('reason', 'Reason for unapproving')->rules('required');
    }
}
