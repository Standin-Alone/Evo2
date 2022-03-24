<?php

namespace App\Modules\UserManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Ramsey\Uuid\Uuid;
use Mail;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Modules\UserManagement\Models\UserManagement;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\Login\Models\OTP;
class AccountCreationController extends Controller
{
   
    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
  
    public function account_creation_index($user_id){
        
        $clean_user_id = base64_decode($user_id);        
        $get_regions = db::table('geo_map')->select('reg_code','reg_name')->distinct()->get();
        $get_agency = db::table('agency')->get();
        $get_roles = db::table('roles')->where('rfo_use','0')->get();
        $get_programs = db::table('programs')->where('status',1)->get();

        $check_user = db::table('users')->where('user_id',$clean_user_id)->first();
        $check_program = db::table('program_permissions')->where('user_id',$clean_user_id)->first();

        if(isset($check_user->is_created) &&  $check_user->is_created == '0' && $check_program){
            return view("UserManagement::account-creation",compact('get_regions','get_agency','get_roles','get_programs','user_id','clean_user_id','check_user','check_program'));
        }else{
            return view("UserManagement::404_error");
        }
    
        
    }


    public function send_account_creation_link(){
        $result = '';
        $user_id = Uuid::uuid4();
        $email = request('email');
        $program = request('program');
        $base64_user_id = base64_encode($user_id);
        $url =  url('/user/account-creation/'.$base64_user_id);   
        

    

        $create_account = db::table('users')->insert([
            'user_id'  => $user_id,
            'email'    => $email,
            'username' => $email,

        ]);
        $create_program = db::table('program_permissions')->insert([            
            "program_id" =>  $program  ,
            "user_id" => $user_id,                
        ]);
        
        
        
      
            Mail::send('UserManagement::account-creation-email', ["link" => $url], function ($message) use ($email) {
                $message->to($email)->subject('User Account Creation Link');                
            }); 
            if(count(Mail::failures()) == 0 ){
                if($create_account && $create_program){
                    $result = ['message'=>'Successfully send a link.','result'=>'success'];
                } else{
                    $result = ['message'=>'Failed to send a link.','result'=>'error'];
                }  
            }else{
                $result = ['message'=>'Failed to send a link.','result'=>'error'];
            }
        
        return json_encode($result);

    }




    public function filter_province($region_code)
    {       
        $get_province = db::table('geo_map')
                            ->select('prov_code','prov_name')
                            ->where('reg_code',$region_code)
                            ->distinct()->get();
        return json_encode($get_province);
    }


    public function filter_municipality($province_code)
    {       
        $get_municipality = db::table('geo_map')
                            ->select('mun_code','mun_name')
                            ->where('prov_code',$province_code)
                            ->distinct()->get();
        return json_encode($get_municipality);
    }

    public function filter_barangay($region_code,$province_code,$municipality_code)
    {       
        $get_barangay = db::table('geo_map')
                            ->select('bgy_code','bgy_name')
                            ->where('reg_code',$region_code)
                            ->where('prov_code',$province_code)
                            ->where('mun_code',$municipality_code)
                            ->distinct()->get();
        return json_encode($get_barangay);
    }

    public function filter_role($agency_loc)
    {       
        $get_roles = db::table('roles')                          
                            ->where('rfo_use',($agency_loc == 'RFO' ? '1' : '0' ) )
                            ->whereNotIn('role_id',$agency_loc == 'RFO' ? [5,6,7,9,11,12,16,17,18] : [14,15,3,1]  )
                            ->get();
        return json_encode($get_roles);
    }

    public function store(){

        $result = '';
        try{
            $user_id         = request('user_id');            
            $first_name     = request('first_name');
            $middle_name    = request('middle_name');
            $last_name      = request('last_name');
            $ext_name       = request('ext_name');
            $email          = request('email');
            $contact        = request('contact');        
            $agency_loc     = request('agency_loc');
            $role           = request('role');
            $agency         = request('agency');
            $program        = request('program');
            $region         = request('region');
            $province       = request('province');
            $municipality   = request('municipality');
            $barangay       = request('barangay');
            $password       = request('password');
            
            $geo_code = db::table('geo_map')
                            ->where('reg_code',$region)
                            ->where('prov_code',$province)
                            ->where('mun_code',$municipality)   
                            ->where('bgy_code',$barangay)
                            ->first();
            if($geo_code){
                db::table('users')
                        ->where('user_id',$user_id)
                        ->update([
                                'is_created' => '1',
                                'agency'  => $agency,
                                'agency_loc'  => $agency_loc,                                        
                                'password'  => bcrypt($password),                                        
                                'geo_code'  => $geo_code->geo_code,
                                'reg' =>$region,
                                'prov' =>$province,
                                'mun' =>$municipality,
                                'bgy' =>$barangay,
                                'first_name' => $first_name,
                                'middle_name' => $middle_name,
                                'last_name' => $last_name,
                                'ext_name' => $ext_name,
                                'contact_no' => $contact,
                                'first_login' =>'0'
                ]);
            
            
                db::table('program_permissions')
                ->where('user_id',$user_id)
                ->update([
                    "role_id" => $role,                      
                ]);
                
                $get_role = db::table('roles')->where('role_id',$role)->first()->role;
                ;

                $result = json_encode(['message'=>'Successfully created your account.You will redirect to Login Page','result'=>'success']);
            }else{
                $result = json_encode(['message'=>'','result'=>'error']);
            }


        }catch(\Exception $e){
            return json_encode(['message'=>'Something went wrong','result'=>'error']);
        }
        return $result;
    }

}   
