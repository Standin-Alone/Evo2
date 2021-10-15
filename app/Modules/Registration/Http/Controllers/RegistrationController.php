<?php

namespace App\Modules\Registration\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Registration\Models\Registration;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class RegistrationController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */

    public function index(){   
        try {
            if(session('supplier_id')){
                $supplier_id = session('supplier_id');
                $checksupplier = DB::table('users')
                ->where('other_info','=',$supplier_id)
                ->exists();
                if($checksupplier){                    
                    back()->with('success','You are registered! You may now proceed to Login.');
                    return $this->getRegion();
                }else{                    
                    return $this->getRegion();
                }
                
            }else{
                session()->forget('supplier_id');
                return redirect('uservalidation/');
            }
        } catch (\Throwable $th) {
            session()->forget('supplier_id');
            return abort(404);
        }   
        
    }

    public function validateid(Request $request){
        try {
            $input = $request->all();
            $supplier_id = $input['id'];
            if($supplier_id){
                $checkSupplier = DB::table('supplier')
                ->where('supplier_id','=',$supplier_id)
                ->exists();
                if($checkSupplier){
                    $request->session()->put('supplier_id',$supplier_id);
                    back()->with('success','You are registered! You may now proceed to Login.');
                    return $this->getRegion();
                }else{                   
                    return $this->getRegion();
                }
                
            }else{
                $request->session()->forget('supplier_id');
                return abort(404);
            }
        } catch (\Throwable $th) {
            $request->session()->forget('supplier_id');
            return abort(404);
        }
            
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

    public function signup(Request $request){
        $request->validate([
            'First_name'=>'required',
            'Last_name'=>'required',
            'Region'=>'required',
            'Province'=>'required',
            'Municipality'=>'required',
            'Barangay'=>'required',
            'Email'=>'required|email|unique:users,email',
            'Contact_Number'=>'required|numeric|min:11|unique:users,contact_no',
            'Username'=>'required',
            'Password'=>'required_with:password|min:8',
            'ReEnter_Password'=>'required_with:password|same:Password|min:8',
        ]);

        $getGeoCode = DB::table('geo_map')->select('geo_code')
        ->where('reg_code', '=', $request->Region)
        ->where('prov_code', '=', $request->Province)
        ->where('mun_code', '=', $request->Municipality)
        ->where('bgy_code', '=', $request->Barangay)->first();
        $geo_code = $getGeoCode->geo_code;

        $ldate = date('Y-m-d H:i:s');
        $password = Hash::make($request->Password);

        $query = DB::table('users')->insert([
            'user_id'=>session('supplier_id'),
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
            'other_info'=>session('supplier_id')
        ]);
        if($query){
            // $request->session()->forget('supplier_id');
            return back()->with('success','Data have been successfuly Saved.');
        }else{
            return back()->with('failed','Something went wrong.');
        }
    }
    

}
