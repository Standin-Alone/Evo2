<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\AccessModel;
use File;
use Illuminate\Support\Facades\Storage;
class AccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        
        return view('auth.login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function signIn()
    {


        
        // $modules = [];


        // $get_main_modules = db::table('sys_modules as sm')                              
        //                     ->whereIn('sm.sys_module_id',function($query){
        //                             $query->select('sm.sys_module_id') 
        //                             ->from('sys_modules as sm')
        //                             ->join('sys_access_matrix as sam','sm.sys_module_id','sam.sys_module_id')                                                                                                                                        
        //                             ->where('sm.status', 1)
        //                             ->whereIn('role_id',[3])                                      
        //                             ->get();
        //                     })                                    
        //                     ->groupBy(DB::raw('ifnull(parent_module_id,sys_module_id)'))                                     
        //                     ->get();
                            
                    
        // $get_parent_modules = db::table('sys_modules')
        //                             ->where('has_sub',1)    
        //                             ->where('status',1)                                    
        //                             ->get();

        // $get_sub_modules = db::table('sys_modules as sm')                            
        //                     ->whereIn('sm.sys_module_id',function($query){
        //                             $query->select('sm.sys_module_id') 
        //                             ->from('sys_modules as sm')
        //                             ->join('sys_access_matrix as sam','sm.sys_module_id','sam.sys_module_id')                            
        //                             ->where('sm.status', 1)
        //                             ->whereIn('role_id',[7,7,7])  
        //                             ->whereNotNull('parent_module_id')                                                                                          
        //                             ->get();
        //                     })                        
                               
        //                     ->get();    
          
        
        // session(['role'=>'RFO Program Staff']);
        // session(['main_modules'=>$get_main_modules]);        
        // session(['parent_modules'=>$get_parent_modules]);
        // session(['sub_modules'=>$get_sub_modules]);
        

        // echo json_encode($get_main_modules);
     
   
        
    }

    // change password of first logged in
    public function firstLoggedIn(){
        $user_id = session('uuid');
        $new_pass = request('new_pass');
        $access_model = new AccessModel();

        return $access_model->change_password($user_id,$new_pass);
    }

    // check default pass
    public function checkDefaultPass(){

        $default_pass = request('default_pass');
        $user_id = session('uuid');
        $access_model = new AccessModel();

        return $access_model->check_default_password($user_id,$default_pass);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function get_notifications(){

        session()->forget('notifications');
        $upload_path = 'uploads/notifications';        
        $upload_folder  = $upload_path;
        $filename = session('uuid');

        $get_notif = [];

        if(File::exists($upload_folder.'/'.$filename.'.json')){
            
        
            $get_notification = file_get_contents($upload_folder.'/'.$filename.'.json');            
            $get_notif = json_decode($get_notification);

        }

        return json_encode($get_notif);

    }

    public function read_notification(){


        try{
                $notification_id = request('notification_id');

                $upload_path = 'uploads/notifications';        
                $upload_folder  = $upload_path;
                $filename = session('uuid');

                $get_notif = [];

                if(File::exists($upload_folder.'/'.$filename.'.json')){
                    
                
                    $get_notification = file_get_contents($upload_folder.'/'.$filename.'.json');

                            
                    $get_notif = json_decode($get_notification);
                    $get_link = '';
                    // MARK AS READ THE NOTIF
                    foreach($get_notif as $item_notif){
            
                        if($item_notif->notif_id == $notification_id){
                            $item_notif->status = 'read';
                            $get_link = $item_notif->link;
                        }
                    }
                    

                    $serialize_data = response()->json($get_notif)->getContent();            
                    Storage::disk('notification')->put('/'.$filename.'.json',$serialize_data);

                    return json_encode(["message"=>true,"link"=>$get_link]);          
                }
        }catch(\Exception $e){
            return json_encode(["message"=>false,"error"=>$e->getMessage()]);          

        }


    }
}
