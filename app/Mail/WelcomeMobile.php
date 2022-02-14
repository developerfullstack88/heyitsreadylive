<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeMobile extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data){
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
      $address = env("MAIL_FROM_ADDRESS");
      $subject = $this->data->subject;
      $name = "Hey It's Ready";

      return $this->view('emails.welcome_mobile')
                  ->from($address, $name)
                  ->replyTo($address, $name)
                  ->subject($subject)
                  ->with(['userData' => $this->data]);
    }
}
