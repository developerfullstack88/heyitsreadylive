<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderEmail extends Mailable
{
  use Queueable, SerializesModels;

  public $data;

  public function __construct($data)
  {
      $this->data = $data;
  }

  public function build()
  {
      $address = env("MAIL_FROM_ADDRESS");
      $subject = $this->data['subject'];
      $name = "Hey It's Ready";

      return $this->view('emails.order')
                  ->from($address, $name)
                  //->cc($address, $name)
                  //->bcc($address, $name)
                  ->replyTo($address, $name)
                  ->subject($subject)
                  ->with(['orderData' => $this->data]);
  }
}
