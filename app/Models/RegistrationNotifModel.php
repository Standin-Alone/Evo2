<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
class RegistrationNotifModel extends Model
{
    use HasFactory;
    
    public function regs_send_email($subject,$regs_fullname,$regs_code,$region,$sendto,$messages){
        $to_email = $sendto;
            Mail::send('notification.registration_mail',["email_message" => $messages, "subject" => $subject,'role' => ""], function ($message) use ($to_email,$subject) {
                $message->to($to_email)
                        ->subject($subject);                            
            }); 

        $get_emails =  DB::table('users as u')
            ->join('program_permissions as pp','u.user_Id','pp.user_id')
            ->join('roles as r', 'r.role_id', 'pp.role_id')                            
            ->where('r.role_id', 4)
            ->where('reg', $region)
            ->pluck('u.email');
        $subject = "For Approval";
        $messages = $regs_fullname." with registration code of ".$regs_code." is now registered to Intervention Management Platform. you may now review and approve the account for the activation. Thank you.";
        // return dd($subject.' - '.$regs_fullname.' - '.$regs_code.' - '.$region.' - '.$sendto.' - '.$messages);
        foreach($get_emails as $item){
            $to_email = $item;
            Mail::send('notification.registration_mail',["email_message" => $messages, "subject" => $subject,'role' => ""], function ($message) use ($to_email,$subject) {
                $message->to($to_email)
                        ->subject($subject);                            
            });                        
        }

        return 'true';
    }
}
