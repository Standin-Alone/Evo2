<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Voucher extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "voucher";

    protected $fillable = ['voucher_id','rsbsa_no','control_no','reference_no','program_id','fund_id','type','first_name','middle_name','last_name','ext_name','sex','birthday','birth_place','mother_maiden','contact_no','civil_status','reg','prv','mun','brgy','farm_area','seed_class','sub_project','rrp_fertilizer_kind','amount','fund_source'];

    public static function getVoucher()
    {
        $records = DB::table('voucher')->select('voucher_id','rsbsa_no','control_no','reference_no','program_id','fund_id','type','first_name','middle_name','last_name','ext_name','sex','birthday','birth_place','mother_maiden','contact_no','civil_status','reg','prv','mun','brgy','farm_area','seed_class','sub_project','rrp_fertilizer_kind','amount','fund_source');
        return $records;
    }
}
