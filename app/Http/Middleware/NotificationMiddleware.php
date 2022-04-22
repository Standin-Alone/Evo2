<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use File;
class NotificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {      
        session()->forget('notification_count');

        $upload_path = 'uploads/notifications';        
        $upload_folder  = $upload_path;
        $filename = session('uuid');

        $count_unread_notif = 0;
        if(File::exists($upload_folder.'/'.$filename.'.json')){
            
        
            $get_notification = file_get_contents($upload_folder.'/'.$filename.'.json');
            
            $decodeFile = json_decode($get_notification);
            

            foreach($decodeFile as $itemDecodedFile){

                if($itemDecodedFile->status == 'unread'){
                    $count_unread_notif++;
                }
            }
            
            session(["notification_count"=>$count_unread_notif]);            
        }else{
            session(["notification_count"=>$count_unread_notif]);            
        }
    
        return $next($request);
    }


}
