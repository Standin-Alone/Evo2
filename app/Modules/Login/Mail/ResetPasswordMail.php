<?php

namespace App\Modules\Login\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reset_pwd_data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reset_pwd_data)
    {
        $this->reset_pwd_data = $reset_pwd_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Login::mail.reset_password_mail');
    }
}
