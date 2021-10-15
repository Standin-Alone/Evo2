<?php

namespace App\Modules\FarmerModule\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FarmerModule extends Model
{
    use HasFactory;

    public function get_voucher(){
        $data = DB::table('voucher')->select('reference_no', 'first_name', 'middle_name', 'last_name', 'ext_name')->get();
                    
        return $data;
    }

    public function get_fullname_and_rsbsa($ref_no){
        $data = DB::table('voucher')->select('reference_no', 'first_name', 'middle_name', 'last_name', 'ext_name')->where('reference_no', '=', $ref_no)->get();

        return $data;
    }

    // add UNION for excel_exports
    public function get_voucher_transaction($id){
        $data = DB::table('voucher_transaction as vt')
                    ->select('vt.voucher_details_id','vt.reference_no', 'v.first_name', 'v.middle_name', 'v.last_name', 'v.ext_name', 
                    'p.description', 'pi.item_name', 'vt.quantity', 'vt.amount', 'vt.total_amount', 
                    'vt.latitude', 'vt.longitude', 'vt.transac_by_fullname', 'vt.payout_date')
                    ->leftJoin('voucher as v', 'vt.reference_no', '=', 'v.reference_no')
                    ->leftJoin('supplier as s', 'vt.supplier_id', '=', 's.supplier_id')
                    ->leftJoin('supplier_programs as sp', 'vt.sub_program_id', '=', 'sp.sub_id')
                    ->leftJoin('programs as p', 'sp.program_id', '=', 'p.program_id')
                    ->leftJoin('program_items as pi', 'sp.item_id', '=', 'pi.item_id')
                    ->where('vt.reference_no', '=', $id)
                    ->get();

        return $data;
    }

    public function get_voucher_attachments(){
        return $data;
    }

    public function get_markers($ref_no){
        $data = DB::table('voucher_transaction')->where('reference_no', '=', $ref_no)->get();
  
        return $data;
    }
}
