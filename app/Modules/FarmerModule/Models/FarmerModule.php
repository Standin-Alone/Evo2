<?php

namespace App\Modules\FarmerModule\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FarmerModule extends Model
{
    use HasFactory;

    public function get_voucher($program_id){
        $query = DB::table('voucher as v')
                        ->select('v.reference_no', 'v.first_name', 'v.middle_name', 'v.last_name', 'v.ext_name', 'v.program_id')
                        ->whereIn('v.program_id', $program_id)
                        ->get();
                    
        return $query;
    }

    public function get_rrp2_dry_voucher(){
        $query = DB::table('voucher as v')
                        ->select('v.reference_no', 'v.first_name', 'v.middle_name', 'v.last_name', 'v.ext_name', 'v.program_id')
                        ->where('v.program_id', '=', '212a7b35-b251-48a6-9928-3f689321d8b1')
                        ->get();
        
        return $query;
    }

    public function get_rrp2_wet_voucher(){
        $query = DB::table('voucher as v')
                        ->select('v.reference_no', 'v.first_name', 'v.middle_name', 'v.last_name', 'v.ext_name', 'v.program_id')
                        ->where('v.program_id', '=', '9fdb5700-6534-4133-8624-f321afb249cf')
                        ->get();

        return $query;
    }

    public function get_csf_voucher(){
        $query = DB::table('voucher as v')
                        ->select('v.reference_no', 'v.first_name', 'v.middle_name', 'v.last_name', 'v.ext_name', 'v.program_id')
                        ->where('v.program_id', '=', '42383225-3a4e-4e18-8cda-deed9a62775f')
                        ->get();

        return $query;
    }

    public function get_fullname_and_rsbsa($ref_no){
        $data = DB::table('voucher')->select('reference_no', 'first_name', 'middle_name', 'last_name', 'ext_name')->where('reference_no', '=', $ref_no)->get();

        return $data;
    }

    public function get_voucher_transaction($id){
        $query = DB::table('voucher_transaction as vt')
                    ->select('vt.voucher_details_id','vt.reference_no', 'v.first_name', 'v.middle_name', 'v.last_name', 'v.ext_name', 
                    'p.program_id', 'p.description', 'pi.item_name', 'vt.quantity', 'vt.amount', 'vt.total_amount', 
                    'vt.latitude', 'vt.longitude', 'vt.transac_by_fullname', 'vt.payout_date')
                    ->leftJoin('voucher as v', 'vt.reference_no', '=', 'v.reference_no')
                    ->leftJoin('supplier as s', 'vt.supplier_id', '=', 's.supplier_id')
                    ->leftJoin('supplier_programs as sp', 'vt.sub_program_id', '=', 'sp.sub_id')
                    ->leftJoin('programs as p', 'sp.program_id', '=', 'p.program_id')
                    ->leftJoin('program_items as pi', 'sp.item_id', '=', 'pi.item_id')
                    ->where('vt.reference_no', '=', $id)
                    ->get();

        return $query;
    }

    public function get_voucher_attachments(){
        return $data;
    }

    public function get_markers($ref_no){
        $query = DB::table('voucher_transaction')->where('reference_no', '=', $ref_no)->get();
  
        return $query;
    }

    //========================  RCEF RFFA ======================== //
    public function get_kyc_profile(){
        $query = DB::table('kyc_profiles as kp')
                        ->select('kp.dbp_batch_id', 'kp.rsbsa_no', 'fs.program_id', 'kp.first_name', 'kp.middle_name', 'kp.last_name', 'kp.ext_name')
                        ->leftJoin('fund_source as fs', 'fs.fund_id', '=', 'kp.fund_id')
                        ->where('fs.program_id', '=', '37b5fdab-6482-433c-af96-455402d5ef77')
                        ->whereNotNull('kp.dbp_batch_id')
                        ->get();

        // $query = DB::select('select kp.dbp_batch_id, kp.rsbsa_no, fs.program_id, kp.first_name, kp.middle_name, kp.last_name, kp.ext_name
        //                     from kyc_profiles as kp
        //                     left join fund_source as fs on fs.fund_id = kp.fund_id
        //                     where fs.program_id = "37b5fdab-6482-433c-af96-455402d5ef77" and kp.dbp_batch_id is not null');

        return $query;
    }

    public function get_rffa_fullname_and_rsbsa($rsbsa_no){
        $query = DB::table('kyc_profiles')->select('rsbsa_no', 'first_name', 'middle_name', 'last_name', 'ext_name')->where('rsbsa_no', '=', $rsbsa_no)->get();

        return $query;
    }

    public function get_dbp_batch($dbp_batch_id, $rsbsa_no){
        $query = DB::table('dbp_batch as db')
                        ->select('db.dbp_batch_id', 'db.date', 'p.title', 'kp.first_name', 'kp.middle_name', 'kp.last_name', 'kp.ext_name', 'ee.amount')
                        ->leftJoin('kyc_profiles as kp', 'kp.dbp_batch_id', '=', 'db.dbp_batch_id')
                        ->leftJoin('excel_export as ee', 'ee.kyc_id', '=', 'kp.kyc_id')
                        ->leftJoin('fund_source as fs', 'fs.fund_id', '=', 'kp.fund_id')
                        ->leftJoin('programs as p', 'p.program_id', '=', 'fs.program_id')
                        ->where('db.dbp_batch_id', '=', $dbp_batch_id)
                        ->where('kp.rsbsa_no', '=', $rsbsa_no)
                        ->get();

        return $query;
    }
}
