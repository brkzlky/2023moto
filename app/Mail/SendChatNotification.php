<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class SendChatNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $variables;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($variables)
    {
        $this->variables = $variables;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.new_chat_message')->subject($this->variables['name']." send you a message!")->with([
            'name' => $this->variables['name'],
            'listing_no' => $this->variables['listing_no'],
            'listing_title' => $this->variables['listing_title'],
            'message' => $this->variables['message']
        ]);;
    }
}
