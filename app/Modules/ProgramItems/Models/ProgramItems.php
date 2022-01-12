<?php

namespace App\Modules\ProgramItems\Models;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramItems extends Model
{
    use HasFactory;

    public function get_ProgramItemsDetails($supplier_id){
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

    public function get_ProgramRegion(){
        $session_reg_code = sprintf("%02d", session('reg_code'));
        $get_Region = DB::table('geo_map')
            ->select(DB::raw("reg_code,reg_name"))
            ->where('reg_code',$session_reg_code)
            ->groupBy('reg_name','reg_code')
            ->get();      
        return response()->json($get_Region);
    }

    public function get_ProgramProvince(){
        $session_reg_code = sprintf("%02d", session('reg_code'));
        $get_Province = DB::table('geo_map')
            ->select(DB::raw("prov_code,prov_name"))
            ->where('reg_code', '=', $session_reg_code)
            ->groupBy('prov_name','prov_code')
            ->get();
        return response()->json($get_Province);
    }
}
