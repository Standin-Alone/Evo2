<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Mail;
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

        

        // email from dmd to program focal
        if($role == 'ICTS DMD'){

            $get_emails =  db::table('users as u')
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
        else if($role == 'RFO Program Focal'){


        }
        else if($role == 'Budget Staff'){
            
        }



        return 'true';
    }
}
