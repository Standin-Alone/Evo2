<?php

namespace App\Modules\UserManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class UserManagement extends Model
{

    protected $table = 'users';

    public function get_program_permission(){
        $region = session()->get('region');

        $query = DB::table('program_permissions as pp')
                        ->select('u.user_id', 'u.first_name','u.last_name','u.middle_name','u.ext_name','a.agency_shortname','gr.region')
                        ->leftJoin('roles as r', 'pp.role_id', '=', 'r.role_id')
                        ->leftJoin('programs as p','pp.program_id', '=', 'p.program_id')
                        ->leftJoin('users as u','pp.user_id', '=', 'u.user_id')
                        ->leftJoin('agency as a', 'a.agency_id', '=', 'u.agency')
                        // ->leftJoin('geo_map as g', 'g.geo_code', '=', 'u.geo_code')
                        ->leftJoin('geo_map as g', 'u.reg', '=', 'g.reg_code')
                        ->leftJoin('geo_region as gr', 'gr.code_reg', '=', 'g.reg_code')
                        ->where('pp.status','=', 1)
                        ->when($region, function ($query, $region) {
                            if($region != 13){
                                $query->where('u.reg', '=', $region)->groupBy('u.user_id');
                            }else{
                                $query->groupBy('u.user_id');
                            }
                        })
                        ->get();

        return $query;
    }

    public function show_user_details($uuid){
        $query = DB::table('program_permissions as pp')
                        ->select('p.title', 'p.description', 'u.email', 'u.contact_no', 'r.role')
                        ->leftJoin('roles as r', 'pp.role_id', '=', 'r.role_id')
                        ->leftJoin('programs as p','pp.program_id', '=', 'p.program_id')
                        ->leftJoin('users as u','pp.user_id', '=', 'u.user_id')
                        ->where('u.user_id', '=', $uuid)
                        ->get();
        return $query;
    }

    public function add_new_user_role($user_id, $role_id, $program_id, $status){
        $query = DB::table('program_permissions')
                        ->insert([
                            'role_id' => $role_id,
                            'program_id' => $program_id,
                            'user_id' => $user_id,
                            'status' => $status,
                        ]);

        return $query;
    }

    public function get_user(){
        $region = session()->get('region');

        $query = DB::table('users as u')
                        ->leftJoin('geo_map as g', 'g.geo_code', '=', 'u.geo_code')
                        ->when($region, function ($query, $region) {
                            if($region != 13){
                                $query->where('u.reg', '=', $region)->groupBy('u.user_id');
                            }else{
                                $query->groupBy('u.user_id');
                            }
                        })
                        ->get();
                    
        return $query;
    }

    public function get_program(){
        $query = DB::table('programs')
                        ->select('program_id', 'title', 'shortname', 'description')
                        ->get();

        return $query;
    }

    public function get_agency(){

        $query = DB::table('agency')
                        ->select('agency_id', 'agency_shortname')
                        ->groupBy('agency_shortname')
                        ->get();
                      
        return $query;
    }

    public function get_region(){
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

    public function get_role(){
        $region = session()->get('region');

        $query = DB::table('roles')
                        ->select('role_id','role')
                        ->when($region, function ($query, $region) {
                            if($region != 13){
                                $query->where('rfo_use', '=', 1)->groupBy('role_id');
                            }else{
                                $query->groupBy('role_id');
                            }
                        })
                        ->get();

        return $query;
    }

    public $timestamps = false;
  
}
