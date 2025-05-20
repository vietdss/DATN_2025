<?php

namespace App\Mail;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PostUnapprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $item;
    public $reason; // Thêm thuộc tính reason

    public function __construct(Item $item, $reason)
    {
        $this->item = $item;
        $this->reason = $reason; // Gán reason
    }

    public function build()
    {
        return $this->subject('Bài đăng của bạn không được duyệt')
                    ->view('emails.post_unapproved')
                    ->with([
                        'item' => $this->item,
                        'reason' => $this->reason, // Truyền reason vào view
                    ]);
    }
}
