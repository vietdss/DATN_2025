<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewItemSubmitted extends Mailable
{
    use Queueable, SerializesModels;
    public $item;

    /**
     * Create a new message instance.
     */
    public function __construct($item)
    {
        $this->item = $item;
    }

    public function build()
    {
        return $this->subject('Bài viết mới cần duyệt')
            ->view('emails.submitted_item');
    }
}
