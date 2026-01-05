<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $code) {}

    public function build()
    {
        return $this->subject('Kode OTP Reset Password')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->view('emails.otp')
            ->with(['code' => $this->code]);
    }
}
