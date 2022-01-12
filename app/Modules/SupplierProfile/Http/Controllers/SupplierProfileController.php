<?php

namespace App\Modules\SupplierProfile\Http\Controllers;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\GlobalNotificationModel;
use App\Modules\SupplierProfile\Models\SupplierProfile;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class SupplierProfileController extends Controller
{

    public function __construct(Request $request)
    {
        $this->QuerySupplierProfile = new SupplierProfile();
    }

    public function index()
    {
        return view("SupplierProfile::index");
    }

    public function getSupplierProfileList(Request $request)
    {   
        $session_reg_code = sprintf("%02d", session('reg_code'));     
        if ($request->ajax()) {
            $data = DB::table('supplier as s')
                    ->select(DB::raw("s.supplier_id,s.supplier_name,sg.group_name as supplier_group,s.address,
                        AES_DECRYPT(s.bank_account_no,'".session('private_secret_key')."') as bank_account_no,
                        s.email,s.contact"))
                    ->leftJoin('supplier_group as sg','sg.supplier_group_id','s.supplier_group_id')                    
                    // ->where('reg_code',$session_reg_code)
                    ->get();
            return Datatables::of($data)->make(true);
        }
    }

    public function getSupplierProfileDetails(Request $request){
        $supplier_id = $request->supplier_id;
        return $this->QuerySupplierProfile->get_SupplierProfileDetails($supplier_id);
    }

    public function getSupplierGroup(Request $request){
        return $this->QuerySupplierProfile->get_SupplierGroup();
    }

    public function getSupplierRegion(){        
        return $this->QuerySupplierProfile->get_SupplierRegion();
    }

    public function getSupplierProvince(Request $request){
        $reg_code = $request->reg_code;
        return $this->QuerySupplierProfile->get_SupplierProvince($reg_code);
    }

    public function getSupplierMunicipality(Request $request){
        $reg_code = $request->reg_code;
        $prov_code = $request->prov_code;
        return $this->QuerySupplierProfile->get_SupplierMunicipality($reg_code,$prov_code);
    }

    public function getSupplierBarangay(Request $request){
        $reg_code = $request->reg_code;
        $prov_code = $request->prov_code;
        $mun_code = $request->mun_code;
        return $this->QuerySupplierProfile->get_SupplierBarangay($reg_code,$prov_code,$mun_code);
    }

    public function getSupplierBank(){
        return $this->QuerySupplierProfile->get_SupplierBank();
    }

    public function Saving_SupplierProfile(Request $request)    
    {
        $params = [
            'supplier_id' => $request->supplier_id,
            'suppliergroup' => $request->selectSupplierGroup,
            'SupplierName' => $request->txtSupplierName,
            'SupplierAddress' => $request->txtSupplierAddress,
            'SupplierRegion' => $request->selectSupplierRegion,
            'SupplierProvince' => $request->selectSupplierProvince,
            'SupplierCity' => $request->selectSupplierCity,
            'SupplierBarangay' => $request->selectSupplierBarangay,
            'SupplierBusinessPermit' => $request->txtSupplierBusinessPermit,
            'SupplierEmail' => $request->txtSupplierEmail,
            'SupplierContact' => $request->txtSupplierContact,
            'OwnerFirstName' => $request->txtOwnerFirstName,
            'OwnerMiddleName' => $request->txtOwnerMiddleName,
            'OwnerLastName' => $request->txtOwnerLastName,
            'OwnerExtName' => $request->txtOwnerExtName,
            'bankcode' => $request->selectOwnerBankName,
            'banklongname' => $request->txtbanklongname,
            'OwnerAcctName' => $request->txtOwnerAcctName,
            'OwnerAcctNo' => $request->txtOwnerAcctNo,
            'OwnerPhoneNo' => $request->txtOwnerPhoneNo,
            'actionbutton' => $request->actionbutton
        ];
        if($params['actionbutton'] == "INSERT"){
            return $this->QuerySupplierProfile->insert_SupplierProfile($params);   
        }else if($params['actionbutton'] == "EDIT"){
            return $this->QuerySupplierProfile->update_SupplierProfile($params);  
        }
              
    }

    public function Removing_SupplierProfile(Request $request)    
    {
        return $this->QuerySupplierProfile->remove_SupplierProfile($request->supplier_id);     
    }
}
