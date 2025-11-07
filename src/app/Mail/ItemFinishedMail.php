<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Item;

class ItemFinishedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    public function build()
    {
        return $this->subject('商品取引が完了しました')
            ->view('emails.item_finished');
    }
}
