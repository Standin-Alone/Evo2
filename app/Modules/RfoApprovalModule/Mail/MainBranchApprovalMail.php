<?php

namespace App\Modules\RfoApprovalModule\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MainBranchApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $branch_approval;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($branch_approval)
    {
        $this->$branch_approval = $branch_approval;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('RfoApprovalModule::mail.rfo_approval_status_mail');
    }
}
