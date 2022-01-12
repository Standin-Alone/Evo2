<?php

namespace App\Modules\SupplierProfile\Models;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierProfile extends Model
{
    use HasFactory;

    public function get_SupplierProfileDetails($supplier_id){
        $get_Details = DB::table('supplier as s')
            ->select(DB::raw("s.supplier_id,s.supplier_name,s.supplier_group_id,sg.group_name,s.address,s.email,s.contact,s.business_permit,s.owner_first_name,s.owner_middle_name,s.owner_last_name,s.owner_ext_name,s.owner_phone,s.geo_code,
            s.reg,g.reg_name,s.prv,g.prov_name,s.mun,g.mun_name,s.brgy,g.bgy_name,s.bank_long_name,s.bank_short_name,
            AES_DECRYPT(s.bank_account_no,'".session('private_secret_key')."') as bank_account_no,s.bank_account_name,s.date_created"))
            ->leftJoin('supplier_group as sg','sg.supplier_group_id','s.supplier_group_id')
            ->leftJoin('geo_map as g','g.geo_code','s.geo_code')
            ->where('s.supplier_id',$supplier_id)
            ->get();  
        return response()->json($get_Details);
    }

    public function get_SupplierGroup(){
        $get_Group = DB::table('supplier_group')
            ->select(DB::raw("supplier_group_id,group_name"))
            ->groupBy('group_name')
            ->get();      
        return response()->json($get_Group);
    }

    public function get_SupplierRegion(){
        $session_reg_code = sprintf("%02d", session('reg_code'));
        $get_Region = DB::table('geo_map')
            ->select(DB::raw("reg_code,reg_name"))
            ->where('reg_code',$session_reg_code)
            ->groupBy('reg_name','reg_code')
            ->get();      
        return response()->json($get_Region);
    }

    public function get_SupplierProvince($reg_code){
        $get_Province = DB::table('geo_map')
            ->select(DB::raw("prov_code,prov_name"))
            ->where('reg_code', '=', $reg_code)
            ->groupBy('prov_name','prov_code')
            ->get();
        return response()->json($get_Province);
    }

    public function get_SupplierMunicipality($reg_code,$prov_code){
        $get_City = DB::table('geo_map')
            ->select(DB::raw("mun_code,mun_name"))
            ->where('reg_code', '=', $reg_code)
            ->where('prov_code', '=', $prov_code)
            ->groupBy('mun_name','mun_code')
            ->get();
        return response()->json($get_City);
    }

    public function get_SupplierBarangay($reg_code,$prov_code,$mun_code){
        $get_Barangay = DB::table('geo_map')
            ->select(DB::raw("bgy_code,bgy_name"))
            ->where('reg_code', '=', $reg_code)
            ->where('prov_code', '=', $prov_code)
            ->where('mun_code', '=', $mun_code)
            ->groupBy('bgy_name','bgy_code')
            ->orderBy('geo_code')
            ->get();
        return response()->json($get_Barangay);
    }

    public function get_SupplierBank(){
        $get_Bank = DB::table('banks')
            ->select(DB::raw("shortname,name"))
            ->where('shortname','<>','')
            ->groupBy('name')
            ->get();      
        return response()->json($get_Bank);
    }

    public function insert_SupplierProfile($params){
        $insert_SupplierProfile_values = DB::transaction(function() use(&$params){
            DB::table('supplier')->insert([
                'supplier_id' => Uuid::uuid4(),
                'supplier_group_id' => $params['suppliergroup'],
                'supplier_name' => $params['SupplierName'],
                'address' => $params['SupplierAddress'],
                'geo_code' => 1,
                'reg' => $params['SupplierRegion'],
                'prv' => $params['SupplierProvince'],
                'mun' => $params['SupplierCity'],
                'brgy' => $params['SupplierBarangay'],
                'business_permit' => $params['SupplierBusinessPermit'],
                'email' => $params['SupplierEmail'],
                'contact' => $params['SupplierContact'],
                'owner_first_name' => $params['OwnerFirstName'],
                'owner_middle_name' => $params['OwnerMiddleName'],
                'owner_last_name' => $params['OwnerLastName'],
                'owner_ext_name' => $params['OwnerExtName'],            
                'bank_long_name' => $params['banklongname'],
                'bank_short_name' => $params['bankcode'],
                'bank_account_no' => DB::raw("AES_ENCRYPT('".$params['OwnerAcctNo']."', '".session('private_secret_key')."')"),
                'bank_account_name' => $params['OwnerAcctName'],               
                'owner_phone' => $params['OwnerPhoneNo'],
                'created_by_id' => session('user_id'),
                'created_agency' => session('agency_loc'),
                'created_by_fullname' => session('user_fullname'),
                'date_created' => Carbon::now('GMT+8')
            ]);
        });
        return $insert_SupplierProfile_values;
    }

    public function update_SupplierProfile($params){
        $update_SupplierProfile_values = DB::transaction(function() use(&$params){
            DB::table('supplier')
                ->where('supplier_id',$params['supplier_id'])
                ->update([
                'supplier_group_id' => $params['suppliergroup'],
                'supplier_name' => $params['SupplierName'],
                'address' => $params['SupplierAddress'],
                'geo_code' => 1,
                'reg' => $params['SupplierRegion'],
                'prv' => $params['SupplierProvince'],
                'mun' => $params['SupplierCity'],
                'brgy' => $params['SupplierBarangay'],
                'business_permit' => $params['SupplierBusinessPermit'],
                'email' => $params['SupplierEmail'],
                'contact' => $params['SupplierContact'],
                'owner_first_name' => $params['OwnerFirstName'],
                'owner_middle_name' => $params['OwnerMiddleName'],
                'owner_last_name' => $params['OwnerLastName'],
                'owner_ext_name' => $params['OwnerExtName'],            
                'bank_long_name' => $params['banklongname'],
                'bank_short_name' => $params['bankcode'],
                'bank_account_no' => DB::raw("AES_ENCRYPT('".$params['OwnerAcctNo']."', '".session('private_secret_key')."')"),
                'bank_account_name' => $params['OwnerAcctName'],               
                'owner_phone' => $params['OwnerPhoneNo']
            ]);
        });
        return $update_SupplierProfile_values;
    }

    public function remove_SupplierProfile($supplier_id){
        $remove_SupplierProfile_values = DB::transaction(function() use(&$supplier_id){
            DB::table('supplier')
                ->where('supplier_id',$supplier_id)
                ->delete();
        });
        return $remove_SupplierProfile_values;
    }
}
