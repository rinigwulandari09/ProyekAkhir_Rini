<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PengingatMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function build()
    {
        return $this->subject('Informasi / Tugas Baru dari Superadmin')
            ->view('emails.pengingat')
            ->with($this->payload);
    }
}