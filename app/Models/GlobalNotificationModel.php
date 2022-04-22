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

    // SEND NOTIFICATION VIA SOCKET
    public function sendNotification($roles,$region_id,$message,$program_id,$link,$title){



        $program_id = session('role_id') == 19 ? $program_id : session('Default_Program_Id');

        $upload_path = 'uploads/notifications';        
        $upload_folder  = $upload_path;
        
         // create folder for returned disbursement files;
         if(!File::isDirectory($upload_path)){
            
            File::makeDirectory($upload_path, 0775, true);                                
            
        }

        
        $consolidated_notifications = [];
        foreach($roles as $item_role){
          
            
            $get_users = db::table('program_permissions as pp')
                                ->leftJoin('users as u','u.user_id','pp.user_id')
                                ->where('role_id',$item_role)
                                ->where('reg',$region_id)
                                ->where('program_id',$program_id)
                                ->get();
            $notif_id = Uuid::uuid4();



            if($get_users){



    
                foreach($get_users as $user_data){
                
                    if(isset($user_data->user_id)){
                            
                        $notification_array = [[
                            "notif_id"  =>$notif_id,
                            "title"     => $title,            
                            "senderName" => session('first_name').' '.session('last_name'),
                            "from"     => session('uuid'),            
                            "message"  => $message,
                            "to"       => $user_data->user_id, 
                            "date"     => date('Y/m/d h:i A'),
                            "role"     =>$item_role,
                            "link"     =>$link,
                            "status"   => "unread"
                        ]];            

                        

                        $filename = isset($user_data->user_id) ? $user_data->user_id : 'none';
            
                        if(File::exists($upload_folder.'/'.$filename.'.json')){

                            $get_notification = file_get_contents($upload_folder.'/'.$filename.'.json');
                            $tempArray = json_decode($get_notification);

                            $tempArray[] = $notification_array[0] ;
                            $serialize_data = json_encode($tempArray);

                            Storage::disk('notification')->put('/'.$filename.'.json',$serialize_data);
                        }else{
                            // upload notification log files
                            $serialize_data = response()->json($notification_array)->getContent();            
                            Storage::disk('notification')->put('/'.$filename.'.json',$serialize_data);
                        }

                        array_push($consolidated_notifications,$notification_array[0]);
                    }
                }
            }
        }
        
        return json_encode($consolidated_notifications);

    }
    
}
