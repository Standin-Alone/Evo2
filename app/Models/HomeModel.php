<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Mail;
class HomeModel extends Model
{
    use HasFactory;


    public function send_email($role,$region,$message){




            $region = $region;                
          
            $subject = '';
         

                if($role == 'RFO Budget Staff'){
                    $subject =  "For Endorsement";
                }else{
                    $subject =  "For Approval";
                }

            


            if($role == 'ICTS_DMD'){

                $get_emails =  db::table('users as u')
                            ->join('program_permissions as pp','u.user_Id','pp.user_id')
                            ->join('roles as r', 'r.role_id', 'pp.role_id')                            
                            ->where('role', 'RFO Program Focal')
                            ->where('reg', DB::table('geo_map')->where('reg_name',$region)->first()->reg_code)
                            ->pluck('u.email');     

                $message = "You have new kyc profiles to generate endorsement.";

                // get emails
                foreach($get_emails as $item){
                    $to_email = $item;

                    Mail::send('notif',["message" => $message ], function ($message) use ($to_email,$subject) {
                        $message->to($to_email)
                                ->subject($subject);                            
                    });                         
                }
            }



        return 'true';
    }
}
