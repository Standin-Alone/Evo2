<?php

namespace App\Modules\UserManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
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
        $get_roles = db::table('roles')->get();
        return view("UserManagement::index",compact('get_regions','get_agency','get_roles'));
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

    public function filter_barangay($municipality_code)
    {       
        $get_barangay = db::table('geo_map')
                            ->select('bgy_code','bgy_name')
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

        $first_name = request('first_name');
        $middle_name = request('first_name');
        $last_name = request('first_name');
        $ext_name = request('ext_name');
        $email = request('email');
        $contact = request('contact');        
        $agency_loc = request('agency_loc');
        $role = request('role');
        $agency = request('agency');
        $region = request('region');
        $province = request('province');
        $municipality = request('municipality');
        $barangay = request('barangay');
    }
    
}
