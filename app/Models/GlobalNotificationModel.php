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
        $role_name = session('role_name_sets');
        $role_name = $role_name[0];
        // subject per role
        if($role == 'ICTS DMD'){
            $subject =  "For Endorsement";
        }else{
            $subject =  "For Approval";
        }        
        
        // email from dmd to program focal
        if($role == "ICTS DMD"){

            $get_emails =  DB::table('users as u')
                        ->join('program_permissions as pp','u.user_Id','pp.user_id')
                        ->join('roles as r', 'r.role_id', 'pp.role_id')                            
                        ->where('r.role_id', 4)
                        ->where('reg', DB::table('geo_map')->where('reg_name',$region)->first()->reg_code)
                        ->pluck('u.email');                 

            // get emails
            foreach($get_emails as $item){
                $to_email = $item;

                Mail::send('notification.upload_mail',["email_message" => $messages, "subject" => $subject,'role' => $role_name], function ($message) use ($to_email,$subject) {
                    $message->to($to_email)
                            ->subject($subject);                            
                });                         
            }
        }
        // email from RFO Program Focal Staff to RFO Focal Supervisor
        else if($role == 4){  

            $get_emails =  DB::table('users as u')
                        ->join('program_permissions as pp','u.user_Id','pp.user_id')
                        ->join('roles as r', 'r.role_id', 'pp.role_id')                            
                        ->where('r.role_id', 8)
                        ->where('reg', $region)
                        ->pluck('u.email'); 

            // get emails
            foreach($get_emails as $item){
                $to_email = $item;

                Mail::send('notification.upload_mail',["email_message" => $messages, "subject" => $subject,'role' => $role_name], function ($message) use ($to_email,$subject) {
                    $message->to($to_email)
                            ->subject($subject);                            
                });                         
            }      

        }
        // email from RFO Focal Supervisor to RFO RED Staff
        else if($role == 8){

            $get_emails =  DB::table('users as u')
                        ->join('program_permissions as pp','u.user_Id','pp.user_id')
                        ->join('roles as r', 'r.role_id', 'pp.role_id')                            
                        ->where('r.role_id', 10)
                        ->where('reg', $region)
                        ->pluck('u.email');                 

            // get emails
            foreach($get_emails as $item){
                $to_email = $item;

                Mail::send('notification.upload_mail',["email_message" => $messages, "subject" => $subject,'role' => $role_name], function ($message) use ($to_email,$subject) {
                    $message->to($to_email)
                            ->subject($subject);                            
                });                         
            }

        }

        return 'true';
    }
}
