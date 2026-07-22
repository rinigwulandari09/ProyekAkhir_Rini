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
        $judul = $this->payload['judul'] ?? 'Informasi / Tugas Baru';
        return $this->subject('Tugas dari Superadmin: ' . $judul)
            ->view('emails.pengingat')
            ->with($this->payload);
    }
}