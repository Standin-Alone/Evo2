<?php

namespace App\Modules\UserManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Ramsey\Uuid\Uuid;
use Mail;
use Illuminate\Support\Str;
class UserManagementController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $get_regions = db::table('geo_map')->select('reg_code','reg_name')->distinct()->get();
        $get_agency = db::table('agency')->get();
        $get_roles = db::table('roles')->where('rfo_use','0')->get();
        $get_programs = db::table('programs')->get();
        return view("UserManagement::index",compact('get_regions','get_agency','get_roles','get_programs'));
    }


    public function email(){
        return view('UserManagement::user-account');
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
                            ->get();
        return json_encode($get_roles);
    }


    public function checkEmail()
    {           
        $get_email = request('email');
        $check_email = db::table('users')->where('email',$get_email)->get();

        if($check_email->isEmpty()){
            
            return 'true';
        }else{
            return 'false';
        }
        
    }

    public function store(){
        
        try{
            $user_id        = Uuid::uuid4();
            $random_password = Str::random(4);
            $first_name     = request('first_name');
            $middle_name    = request('first_name');
            $last_name      = request('first_name');
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
            
            $geo_code = db::table('geo_map')
                            ->where('reg_code',$region)
                            ->where('prov_code',$province)
                            ->where('mun_code',$municipality)   
                            ->where('bgy_code',$barangay)
                            ->first();

            db::table('users')
                                ->insertGetId([
                                    'user_id'  => $user_id,
                                    'agency'  => $agency,
                                    'agency_loc'  => $agency_loc,
                                    'username'  => $email,
                                    'password'  => bcrypt($random_password),
                                    'email'  => $email,
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
            ]);
        
        
            db::table('program_permissions')->insert([
                "role_id" =>$role,
                "program_id" => $program,
                "user_id" => $user_id,                
            ]);
            
            $get_role = db::table('roles')->where('role_id',$role)->first()->role;
            Mail::send('UserManagement::user-account', ["username" => $email,"password" => $random_password,"role" => $get_role], function ($message) use ($email, $random_password) {
                $message->to($email)
                        ->subject('User Account Credentials')
                        ->from("support.sadd@da.gov.ph");
            });

            return 'true';
        }catch(\Exception $e){
            return $e;
        }
    }
    
}
