<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
class GlobalNotificationModel extends Model
{
    use HasFactory;
    
    public function send_email($role,$region,$messages){

        $subject = '';

        // subject per role
        if($role == 'ICTS DMD'){
            $subject =  "For Endorsement";
        }else{
            $subject =  "For Approval";
        }        

        // $to_email = 'petergvillasis@gmail.com';

        // Mail::send('notification.upload_mail',["email_message" => $messages, "subject" => $subject,'role' => $role[0]], function ($message) use ($to_email,$subject) {
        //     $message->to($to_email)
        //             ->subject($subject);                            
        // });  
        
        // email from dmd to program focal
        if($role == 'ICTS DMD'){

            $get_emails =  DB::table('users as u')
                        ->join('program_permissions as pp','u.user_Id','pp.user_id')
                        ->join('roles as r', 'r.role_id', 'pp.role_id')                            
                        ->where('role', 'RFO Program Focal')
                        ->where('reg', DB::table('geo_map')->where('reg_name',$region)->first()->reg_code)
                        ->pluck('u.email');                 

            // get emails
            foreach($get_emails as $item){
                $to_email = $item;

                Mail::send('notification.upload_mail',["email_message" => $messages, "subject" => $subject,'role' => $role], function ($message) use ($to_email,$subject) {
                    $message->to($to_email)
                            ->subject($subject);                            
                });                         
            }

        }
        // email from dmd to RFO Budget Staff
        else if($role == 'RFO Program Focal'){  

            $get_emails =  DB::table('users as u')
                        ->join('program_permissions as pp','u.user_Id','pp.user_id')
                        ->join('roles as r', 'r.role_id', 'pp.role_id')                            
                        ->where('role', 'RFO Budget Staff')
                        ->where('reg', $region)
                        ->pluck('u.email'); 

            // get emails
            foreach($get_emails as $item){
                $to_email = $item;

                Mail::send('notification.upload_mail',["email_message" => $messages, "subject" => $subject,'role' => $role], function ($message) use ($to_email,$subject) {
                    $message->to($to_email)
                            ->subject($subject);                            
                });                         
            }      

        }
        // email from dmd to RFO Disbursement Officer
        else if($role == 'RFO Budget Staff'){

            $get_emails =  DB::table('users as u')
                        ->join('program_permissions as pp','u.user_Id','pp.user_id')
                        ->join('roles as r', 'r.role_id', 'pp.role_id')                            
                        ->where('role', 'RFO Disbursement Staff')
                        ->where('reg', $region)
                        ->pluck('u.email');                 

            // get emails
            foreach($get_emails as $item){
                $to_email = $item;

                Mail::send('notification.upload_mail',["email_message" => $messages, "subject" => $subject,'role' => $role], function ($message) use ($to_email,$subject) {
                    $message->to($to_email)
                            ->subject($subject);                            
                });                         
            }

        }
        // email from dmd to Regional Executive Director
        else if($role == 'RFO Disbursement Staff'){

            $get_emails =  DB::table('users as u')
                        ->join('program_permissions as pp','u.user_Id','pp.user_id')
                        ->join('roles as r', 'r.role_id', 'pp.role_id')                            
                        ->where('role', 'Regional Executive Director')
                        ->where('reg', $region)
                        ->pluck('u.email');                 

            // get emails
            foreach($get_emails as $item){
                $to_email = $item;

                Mail::send('notification.upload_mail',["email_message" => $messages, "subject" => $subject,'role' => $role], function ($message) use ($to_email,$subject) {
                    $message->to($to_email)
                            ->subject($subject);                            
                });                         
            }

        }
        // email from dmd to RFO Disbursement Officer
        else if($role == 'Regional Executive Director'){

            $get_emails =  DB::table('users as u')
                        ->join('program_permissions as pp','u.user_Id','pp.user_id')
                        ->join('roles as r', 'r.role_id', 'pp.role_id')                            
                        ->where('role', 'RFO Disbursement Staff')
                        ->where('reg', $region)
                        ->pluck('u.email'); 

            // get emails
            foreach($get_emails as $item){
                $to_email = $item;

                Mail::send('notification.upload_mail',["email_message" => $messages, "subject" => $subject,'role' => $role], function ($message) use ($to_email,$subject) {
                    $message->to($to_email)
                            ->subject($subject);                            
                });                         
            }

            $add_on =  DB::table('add_on_notif as u')
                        ->where('program_id', session('Default_Program_Id'))
                        ->where('status', 1)
                        ->pluck('u.email'); 
            
            // get emails
            foreach($add_on as $row){
                $to_email_add = $row;

                Mail::send('notification.upload_mail',["email_message" => $messages, "subject" => $subject,'role' => $role], function ($message) use ($to_email_add,$subject) {
                    $message->to($to_email_add)
                            ->subject($subject);                            
                });                         
            }
            
        }

        return 'true';
    }
}
