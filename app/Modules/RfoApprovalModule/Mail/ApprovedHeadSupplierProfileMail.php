<?php

namespace App\Modules\RfoApprovalModule\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApprovedHeadSupplierProfileMail extends Mailable
{
    use Queueable, SerializesModels;

    public $approved_head_supplier_profile;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($approved_head_supplier_profile)
    {
        $this->approved_head_supplier_profile = $approved_head_supplier_profile;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('RfoApprovalModule::mail.approved_updated_head_supplier_profile_mail');
    }
}
