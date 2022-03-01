<?php

namespace App\Modules\DbpReturned\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DbpReturned extends Model
{
    use HasFactory;

    public function get_dbp_return_query(){

        $region = session()->get('region');  

        $query = DB::table('dbp_return as dbp_r')
                        ->select('dbp_r.rsbsa_no', 'dbp_rf.file_name',  'dbp_r.account_number', 'dbp_r.first_name', 'dbp_r.middle_name', 'dbp_r.last_name',   'dbp_r.province', 'dbp_r.city_municipality', 'dbp_r.dbp_status', 'dbp_r.iscancelled')
                        ->leftJoin('dbp_returned_files as dbp_rf', 'dbp_rf.return_file_id', '=', 'dbp_r.return_file_id')
                        ->when($region, function($query, $region){
                            if($region != 13){
                                $query->where('dbp_r.reg_code', '=', $region)
                                      ->orderByDesc('dbp_r.date_uploaded');
                            }
                            else{
                                $query->orderByDesc('dbp_r.date_uploaded');
                            } 
                        })  
                        ->get();

        return $query;

    }
}
