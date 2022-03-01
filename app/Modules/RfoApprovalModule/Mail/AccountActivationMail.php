<?php

namespace App\Modules\RfoApprovalModule\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $update_account_status_v2;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($update_account_status_v2)
    {
        $this->update_account_status_v2 = $update_account_status_v2;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('RfoApprovalModule::mail.rfo_account_activation_mail');
    }
}
