<?php

namespace App\Modules\VoucherListing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Voucher extends Model
{
    public $timestamps = false;
    protected $fillable = ['voucher_id','rsbsa_no','control_no','reference_no','program_id','fund_id','type','first_name','middle_name','last_name','ext_name','sex','birthday','birth_place','mother_maiden','contact_no','civil_status','geo_code','reg','reg_desc','prv','prv_desc','mun','mun_desc','brgy','brgy_desc','farm_area','seed_class','if_4ps','if_ip','if_pwd','sub_project','rrp_fertilizer_kind','amount','amount_val','fund_desc','voucher_season','voucher_status','voucher_remarks','batch_code','encoded_by_id','encoded_by_fullname'];
    protected $table = "voucher";
    use HasFactory;

    public static function getVoucher()
    {
        $records = DB::table('voucher')->select('voucher_id','rsbsa_no','control_no','reference_no','program_id','fund_id','type','first_name','middle_name','last_name','ext_name','sex','birthday','birth_place','mother_maiden','contact_no','civil_status','geo_code','reg','reg_desc','prv','prv_desc','mun','mun_desc','brgy','brgy_desc','farm_area','seed_class','if_4ps','if_ip','if_pwd','sub_project','rrp_fertilizer_kind','amount','amount_val','fund_desc','voucher_season','encoded_by_id','encoded_by_fullname');
        return $records;
    }

}
