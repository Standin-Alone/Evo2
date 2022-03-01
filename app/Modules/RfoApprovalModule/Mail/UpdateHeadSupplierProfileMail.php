<?php

namespace App\Modules\RfoApprovalModule\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateHeadSupplierProfileMail extends Mailable
{
    use Queueable, SerializesModels;

    public $head_supplier_profile_approval;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($head_supplier_profile_approval)
    {
        $this->head_supplier_profile_approval = $head_supplier_profile_approval;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('RfoApprovalModule::mail.update_head_supplier_profile_notif_mail');
    }
}
