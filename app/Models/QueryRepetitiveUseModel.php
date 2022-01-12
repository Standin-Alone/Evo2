<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QueryRepetitiveUseModel extends Model
{
    use HasFactory;

    // Get program
    public function get_program_on_program_permission($program_ids){
        $region = session()->get('region');

        $query = DB::table('program_permissions as pp')
                        ->select('pp.program_id' ,'p.title', 'p.shortname', 'p.description') 
                        ->leftJoin('programs as p', 'pp.program_id', '=', 'p.program_id')
                        ->leftJoin('users as u', 'pp.user_id', '=', 'u.user_id')
                        ->leftJoin('geo_map as g', 'u.geo_code', '=', 'g.geo_code')
                        ->when($region, function($query, $region) use($program_ids){
                            if($region != 13){
                                $query->where('u.reg', '=', $region)
                                      ->whereIn('pp.program_id', $program_ids)
                                      ->groupBy('p.program_id');
                            }
                            else{
                                $query->whereIn('pp.program_id', $program_ids)->groupBy('p.program_id');
                            } 
                        })
                        ->get();

        return $query;
    }

    // Get Region from geo_region
    public function get_geo_region(){
        $region = session()->get('region');

        $query = DB::table('geo_map as gm')
                        ->select('gr.code_reg', 'gr.region')
                        ->leftJoin('geo_region as gr', 'gr.code_reg', '=', 'gm.reg_code')
                        ->when($region, function($query, $region){
                            if($region != 13){
                                $query->where('gr.code_reg', '=', $region)->groupBy('gr.code_reg');
                            }else{
                                $query->groupBy('gr.code_reg');
                            }
                        })
                        ->get();

        return $query;
    }

    public function get_region_on_geo_map(){
        $region_code = session()->get('region');

        $query = DB::table('geo_map')
                        ->select('reg_code', 'reg_name')
                        ->when($region_code, function($query, $region_code){
                            if($region_code != 13){
                                $query->where('reg_code', '=', $region_code)->groupBy('reg_name');
                            }else{
                                $query->groupBy('reg_name');
                            }
                        })
                        ->get();

        return $query;
    }

    public function get_province_on_geo_map(){
        $region_code = session()->get('region');
        
        $query = DB::table('geo_map')
                        ->select('prov_code', 'prov_name')
                        ->when($region_code, function($query, $region_code){
                            if($region_code != 13){
                                $query->where('reg_code', '=', $region_code)->groupBy('prov_name');
                            }else{
                                $query->groupBy('prov_name');
                            }
                        })
                        ->get();

        return $query;
    }

    // Get All Municipality
    public function get_municipality_on_geo_map(){
        $region_code = session()->get('region');
        $province_code = session()->get('province');

        $query = DB::table('geo_map')
                        ->select('mun_code', 'mun_name')
                        ->when($region_code, function($query, $region_code) use($province_code){
                            if($region_code != 13){
                                $query->where('reg_code', '=', $region_code)
                                      ->where('prov_code', '=', $province_code)
                                      ->groupBy('mun_name');
                            }else{
                                $query->groupBy('mun_name');
                            }
                        })
                        ->get();

        return $query;
    }
}
