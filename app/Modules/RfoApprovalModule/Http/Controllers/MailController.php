<?php

namespace App\Modules\RfoApprovalModule\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Modules\RfoApprovalModule\Mail\AccountActivationMail;
use setasign\Fpdi\Fpdi;

class MailController extends Controller
{
    public static function send_account_status_to_supplier_v2($uuid, $first_access_supplier_profile_code, $company_name, $company_address, $email, $fullname, $contact_no, $reg_shortname, $shortname, $role, $acc_approval_status, $acc_status_name, $date_created, $get_list_checked_arr, $get_list_unchecked_arr, $checked_by_fullname, $activated_by_fullname, $attachmentPDF){

        $update_account_status_v2 = [
                                        'uuid'                                  => $uuid,
                                        // 'srn'                                   => $srn,
                                        'first_access_supplier_profile_code'    => $first_access_supplier_profile_code,
                                        'company_name'                          => $company_name,
                                        'company_address'                       => $company_address,
                                        'email'                                 => $email,
                                        'fullname'                              => $fullname,
                                        'contact_no'                            => $contact_no,
                                        'reg_shortname'                         => $reg_shortname,
                                        'shortname'                             => $shortname,
                                        'role'                                  => $role,
                                        'acc_approval_status'                   => $acc_approval_status,
                                        'acc_status_name'                       => $acc_status_name,
                                        'date_created'                          => $date_created,
                                        'get_list_checked_arr'                  => $get_list_checked_arr,
                                        'get_list_unchecked_arr'                => $get_list_unchecked_arr,
                                        'checked_by_fullname'                   => $checked_by_fullname,
                                        'activated_by_fullname'                 => $activated_by_fullname,
                                        'attachmentPDF'                         => $attachmentPDF
                                    ];

        if($update_account_status_v2['attachmentPDF'] == 0){
            Mail::send('RfoApprovalModule::mail.rfo_account_activation_mail', ['user_data' => $update_account_status_v2], function($message) use ($update_account_status_v2) {
                $message->to($update_account_status_v2['email'])
                        ->subject('Supplier Application | Update Account Status');
            });
        }else{
            Mail::send('RfoApprovalModule::mail.rfo_account_activation_mail', ['user_data' => $update_account_status_v2], function($message) use ($update_account_status_v2, $attachmentPDF) {
                $message->to($update_account_status_v2['email'])
                        ->subject('Supplier Application | Update Account Status')
                        ->attachData($attachmentPDF, $update_account_status_v2['fullname']."-certificate_accreditation.pdf");
            });
        }


    }

    // public static function send_account_status_to_supplier($uuid, $company_name, $company_address, $email, $fullname, $contact_no, $region, $role, $status_name, $status, $activated_by_fullname, $date_created){
    //     $update_account_status = [
    //         'uuid'                   => $uuid,
    //         'company_name'           => $company_name,
    //         'company_address'        => $company_address,
    //         'email'                  => $email,
    //         'fullname'               => $fullname,
    //         'contact_no'             => $contact_no,
    //         'region'                 => $region,
    //         'role'                   => $role,
    //         'status_name'            => $status_name,
    //         'status'                 => $status,
    //         'activated_by_fullname' => $activated_by_fullname,
    //         'date_created'           => $date_created,
    //     ];

    //     Mail::send('RfoApprovalModule::mail.rfo_account_activation_mail', ['user_data' => $update_account_status], function($message) use ($update_account_status) {
    //         $message->to($update_account_status['email'])
    //                 ->subject('Supplier Application | Update Account Status'); 
    //     });

    // }

    public static function send_main_branch_approval_to_merchant($supplier_group_id, $group_name, $email, $created_agency, $created_by_fullname, $approval_status_name, $approval_status, $approved_by_fullname, $date_created){
        $branch_approval = [
                                'supplier_group_id'          => $supplier_group_id,
                                'group_name'                 => $group_name,
                                'email'                      => $email,
                                'created_agency'             => $created_agency,
                                'created_by_fullname'        => $created_by_fullname,
                                'approval_status_name'       => $approval_status_name,
                                'approval_status'            => $approval_status,
                                'approved_by_fullname'       => $approved_by_fullname,
                                'date_created'               => $date_created,
                            ];

        Mail::send('RfoApprovalModule::mail.rfo_approval_status_mail', ['user_data' => $branch_approval], function($message) use ($branch_approval) {
            $message->to($branch_approval['email'])
                    ->subject('Supplier Application | Supplier Main Branch Approval Status'); 
        });
    }

    // Send notification to RFO Approval for head supplier profile updated approval
    public static function send_head_supplier_profile_updating_notification($rfo_email_data, $rfo_fullname, $rfo_role_data, $supplier_name, $supplier_fullname, $email, $contact, $reg, $date_created, $supplier_type){
        $head_supplier_profile_approval = [
                                                'rfo_email_data'        => $rfo_email_data,
                                                'rfo_fullname'          => $rfo_fullname,
                                                'rfo_role_data'         => $rfo_role_data,
                                                'supplier_name'         => $supplier_name,
                                                'supplier_fullname'     => $supplier_fullname,
                                                'email'                 => $email,
                                                'contact'               => $contact,
                                                'reg'                   => $reg,
                                                'date_created'          => $date_created,
                                                'supplier_type'         => $supplier_type,
                                          ];

        if($supplier_type == "main"){
            Mail::send('RfoApprovalModule::mail.update_head_supplier_profile_notif_mail', ['user_data' => $head_supplier_profile_approval], function($message) use ($head_supplier_profile_approval) {
                $message->to($head_supplier_profile_approval['rfo_email_data'])
                        ->subject('Supplier Application | Head Supplier Profile Update Request'); 
            });
        }
        if($supplier_type == "branch"){
            Mail::send('RfoApprovalModule::mail.update_head_supplier_profile_notif_mail', ['user_data' => $head_supplier_profile_approval], function($message) use ($head_supplier_profile_approval) {
                $message->to($head_supplier_profile_approval['rfo_email_data'])
                        ->subject('Supplier Application | Supplier Profile Update Request'); 
            });
        }
    }

    // Send notification to Head Supplier for approved updated profile
    public static function send_approved_update_head_supplier_profile_notification($email, $fullname, $role, $username, $date_created){
        $head_supplier_profile_approval = [
                                            "email"         => $email, 
                                            "fullname"      => $fullname,
                                            "role"          => $role,
                                            "username"      => $username,
                                            "date_created"  => $date_created
                                        ];

        Mail::send('RfoApprovalModule::mail.approved_updated_head_supplier_profile_mail', ['user_data' => $head_supplier_profile_approval], function($message) use ($head_supplier_profile_approval) {
            $message->to($head_supplier_profile_approval['email'])
                    ->subject('Supplier Application | Head Supplier Profile Update Request'); 
        });
    }

}
