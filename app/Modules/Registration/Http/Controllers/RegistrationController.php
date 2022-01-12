<?php

namespace App\Modules\Registration\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Registration\Models\Registration;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use App\Models\RegistrationNotifModel;

class RegistrationController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */

    public function index(){   
        $getRegion = array(
            'reg_loc' => DB::table('geo_map')
            ->select(DB::raw("reg_code,reg_name"))
            ->groupBy('reg_name','reg_code')
            ->get()
        );                    
        return view("Registration::register",$getRegion);        
    }

    public function getRegion(){
        $getRegion = array(
            'reg_loc' => DB::table('geo_map')
            ->select(DB::raw("reg_code,reg_name"))
            ->groupBy('reg_name','reg_code')
            ->get()
        );                    
        return view("Registration::register",$getRegion);
    }

    public function findProvince(Request $request){
        $reg_code = $request->reg_code;
        $getRegion = DB::table('geo_map')
        ->select(DB::raw("prov_code,prov_name"))
        ->where('reg_code', '=', $reg_code)
        ->groupBy('prov_name','prov_code')
        ->get();
        return response()->json($getRegion);
    }

    public function findMunicipality(Request $request){
        $reg_code = $request->reg_code;
        $prov_code = $request->prov_code;
        $getProvince = DB::table('geo_map')
        ->select(DB::raw("mun_code,mun_name"))
        ->where('reg_code', '=', $reg_code)
        ->where('prov_code', '=', $prov_code)
        ->groupBy('mun_name','mun_code')
        ->get();
        return response()->json($getProvince);
    }

    public function findBarangay(Request $request){
        $reg_code = $request->reg_code;
        $prov_code = $request->prov_code;
        $mun_code = $request->mun_code;
        $getMunicipality = DB::table('geo_map')
        ->select(DB::raw("bgy_code,bgy_name"))
        ->where('reg_code', '=', $reg_code)
        ->where('prov_code', '=', $prov_code)
        ->where('mun_code', '=', $mun_code)
        ->groupBy('bgy_name','bgy_code')
        ->get();
        return response()->json($getMunicipality);
    }

    public function saveregistration(Request $request){
        $checkExistName = DB::table('users')
        ->where('last_name',$request->Last_name)
        ->where('first_name',$request->First_name)
        ->exists();
        if($checkExistName){
            return "ExistName";
        }

        $checkExistEmail = DB::table('users')
        ->where('email',$request->Email)
        ->exists();
        if($checkExistEmail){
            return "ExistEmail";
        }

        $checkExistCompany = DB::table('users')
        ->where('company_name',$request->Company_name)
        ->exists();
        if($checkExistCompany){
            return "ExistCompany";
        }
        
        $checkExistUsername = DB::table('users')
        ->where('username',$request->Username)
        ->exists();
        if($checkExistUsername){
            return "ExistUsername";
        }

        $getGeoCode = DB::table('geo_map')->select('geo_code')
        ->where('reg_code', '=', $request->Region)
        ->where('prov_code', '=', $request->Province)
        ->where('mun_code', '=', $request->Municipality)
        ->where('bgy_code', '=', $request->Barangay)->first();
        $geo_code = $getGeoCode->geo_code;

        $ldate = date('Y-m-d H:i:s');
        $password = Hash::make($request->Password);
        $regs_code = Str::random(8);

        $query = DB::table('users')->insert([
            'user_id'=>Uuid::uuid4(),
            'agency'=>null,
            'agency_loc'=>null,
            'username'=>$request->Username,
            'password'=>$password,
            'email'=>$request->Email,
            'geo_code'=>$geo_code,
            'reg'=>$request->Region,
            'prov'=>$request->Province,
            'mun'=>$request->Municipality,
            'bgy'=>$request->Barangay,
            'first_name'=>$request->First_name,
            'middle_name'=>$request->Middle_name,
            'last_name'=>$request->Last_name,
            'ext_name'=>$request->Extention_name,
            'contact_no'=>$request->Contact_Number,
            'date_created'=>$ldate,
            'company_name'=>$request->Company_name,
            'company_address'=>$request->Company_address,
            'regs_code'=>$regs_code,
            'status'=>'0',
        ]);           
            
        $global_model = new RegistrationNotifModel;                
        return $global_model->regs_send_email("Registration",$request->Last_name.','.$request->First_name.' '.$request->Extention_name.' '.$request->Middle_name,$regs_code,$request->Region,$request->Email,"Your account successfully registered! Please check your account activation status. Use this Registration Code: ".$regs_code);  
    }

    public function getregsstatus(Request $request){
        $getRegsStatus = DB::table('users')
        ->select(DB::raw("date_created,status,concat(last_name,', ',concat(first_name,' ',ifnull(ext_name,'')),' ',middle_name) as regs_name,company_name"))
        ->where('regs_code', $request->regs_code)
        ->where('email', $request->regs_email)
        ->get();
        return response()->json($getRegsStatus);
    }
    

}
