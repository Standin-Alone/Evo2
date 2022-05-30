<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use File;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
class GlobalNotificationModel extends Model
{
    use HasFactory;
    
    public function send_email($role,$region,$messages,$agency_id,$program_id){

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
                        ->where('u.agency', $agency_id)
                        ->where('pp.program_id', $program_id)
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
                        ->where('u.agency', session('user_agency_id'))
                        ->where('pp.program_id', session('program_id'))
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
                        ->where('u.agency',  session('user_agency_id'))
                        ->where('pp.program_id', session('program_id'))
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

  // SEND NOTIFICATION VIA SOCKET
    public function sendNotification($user_id,$title,$message,$link){


        $upload_path = 'uploads/notifications';        
        $upload_folder  = $upload_path;
        
         // create folder for returned disbursement files;
         if(!File::isDirectory($upload_path)){
            
            File::makeDirectory($upload_path, 0775, true);                                
            
        }

        $get_user_info = db::table('users as u')
                            ->join('program_permissions as pp','u.user_id','pp.user_id')
                            ->join('roles as r','r.role_id','pp.role_id')
                            ->where('u.user_id',$user_id)
                            ->groupBy('u.user_id')
                            ->first();

        $notification_id = Uuid::uuid4();
        $sender_name =  session('first_name').' '.session('last_name');
        $sender_user_id =  session('uuid');

        $notification_array = [[
            "notif_id"   => $notification_id,
            "title"      => $title,            
            "senderName" => $sender_name,
            "sender_user_id" => $sender_user_id,            
            "message"    => $message,
            "receiver_user_id" => $user_id, 
            "date"       => date('Y/m/d h:i A'),
            "role"       => $get_user_info->role_id,
            "link"       => $link,
            "status"     => "unread"
        ]];   


        if(File::exists($upload_folder.'/'.$user_id.'.json')){

            $get_notification = file_get_contents($upload_folder.'/'.$user_id.'.json');
            $tempArray = json_decode($get_notification);

            $tempArray[] = $notification_array[0];
            $serialize_data = json_encode($tempArray);

            Storage::disk('notification')->put('/'.$user_id.'.json',$serialize_data);

        }else{
            // upload notification log files
            $serialize_data = response()->json($notification_array)->getContent();            
            Storage::disk('notification')->put('/'.$user_id.'.json',$serialize_data);
        }


        return json_encode($notification_array[0]);  
    }

  
}
