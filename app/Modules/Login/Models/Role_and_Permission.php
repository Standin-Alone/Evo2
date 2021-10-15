<?php

namespace App\Modules\Login\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role_and_Permission extends Model
{
    use HasFactory;

    public function get_user_session($uuid){
        $query = DB::table('program_permissions as pp')
                        ->select('r.role_id', 'r.role', 'p.program_id', 'p.title', 'p.description', 
                                's.supplier_id', 's.supplier_name', 'u.user_id', 'u.username', 'g.iso_prv','s.bank_account_name','s.bank_account_no',
                                'u.first_name', 'u.last_name','u.middle_name', 'u.ext_name', 'u.email', 'u.reg', 'u.prov', 'u.mun', 'gr.region', 
                                'g.reg_name', 'g.prov_name', 'mun_name')
                        ->leftJoin('roles as r', 'pp.role_id', '=', 'r.role_id')
                        ->leftJoin('programs as p','pp.program_id', '=', 'p.program_id')
                        ->leftJoin('supplier as s', 'pp.other_info', '=', 's.supplier_id')
                        ->leftJoin('users as u','pp.user_id', '=', 'u.user_id')
                        ->leftJoin('geo_map as g', 'g.geo_code', '=', 'u.geo_code')
                        ->leftJoin('geo_region as gr', 'u.reg', '=', 'gr.code_reg')
                        ->where('pp.status', '=', 1)
                        ->where('u.user_id', '=', $uuid)
                        ->get();

        return $query;
    }

    // Peter: Supplier
    public function get_supplier($user_id){
        $getProgramValue = DB::table('programs as p')
                                ->select(DB::raw("p.program_id,p.title,p.shortname,p.description"))
                                ->leftjoin('program_permissions as pp','pp.program_id','=','p.program_id')
                                ->where('pp.user_id',$user_id)
                                ->get();

        return $getProgramValue;
    }

    // Peter: Default Program
    public function get_default_program($user_id){
        $getDefaultValue = DB::table('programs as p')
                                ->select(DB::raw("p.program_id,p.title,p.shortname,p.description"))
                                ->leftjoin('program_permissions as pp','pp.program_id','=','p.program_id')
                                ->where('pp.user_id',$user_id)
                                ->groupBy('p.program_id')
                                ->orderBy('p.program_id','desc')
                                ->take(1)
                                ->get();

        return $getDefaultValue;
    }

    // Peter: Province List
    public function get_User_Province($user_id){
        $getUserRegion = DB::table('users as u')
                    ->select(DB::raw("u.reg"))
                    ->where('u.user_id',$user_id)
                    ->get();  
            $user_reg_code = "";
            foreach($getUserRegion as $key => $row){
                $user_reg_code = $row->reg;
            }    
                
        $getUserProvince = DB::table('geo_map as geo')
                    ->select(DB::raw("geo.prov_code,geo.prov_name"))   
                    ->where('geo.reg_code',$user_reg_code)
                    ->groupBy('geo.prov_code')
                    ->get();

        return $getUserProvince;
    }


    // Sir AJ session
    public function get_reg_and_prov($uuid){
        $query = DB::table('users as u')
                        ->select('gr.region', 'g.prov_name', 'g.prov_code')
                        ->leftJoin('geo_map as g', 'u.reg', '=', 'g.reg_code')
                        ->leftJoin('geo_region as gr', 'gr.code_reg', '=', 'g.reg_code')
                        ->where('u.user_id', '=', $uuid)
                        ->groupBy('gr.region', 'g.prov_name')
                        ->havingRaw('count(*) > 1')
                        ->get();
        
        $data = [];

        foreach($query as $value){
            $data[$value->region][$value->prov_code] = $value->prov_name;
        }

        return $data;
    } 

    // geo_map
    public function get_region_geo_map($uuid){
        $query = DB::table('users as u')
                        ->select('g.reg_name',)
                        ->leftJoin('geo_map as g', 'u.reg', '=', 'g.reg_code')
                        ->leftJoin('geo_region as gr', 'gr.code_reg', '=', 'g.reg_code')
                        ->where('u.user_id', '=', $uuid)
                        ->groupBy('g.reg_name')
                        ->get();

        return $query;
    }

    public function get_supplier_id($uuid){
        $query = DB::table('program_permissions as pp')
                        ->leftJoin('supplier as s', 'pp.other_info', '=', 's.supplier_id')
                        ->where('user_id', '=', $uuid)
                        ->pluck('s.supplier_id')
                        ->all();
                        
        return $query;
    }

    public function get_list_of_roles(){
        $query = DB::table('roles')
                        ->pluck('role_id')
                        ->all();
                        
        return $query;
    }

    /**
     * example:
     * Program: [Cash and Food, RRP Wet Season, RRP Dry Season]
     */
    public function get_program_in_program_permission($uuid){
        $query = DB::table('program_permissions as pp')
                        ->leftJoin('programs as p', 'pp.program_id', '=', 'p.program_id')
                        ->where('user_id', '=', $uuid)
                        ->pluck('p.description')
                        ->all();

        return $query;
    }

    /**
     * example:
     * Role: [8] Budget officer, [10] Disbursement officer
     * Role: [8, 10]
     */
    public function get_role_in_program_permission($uuid){
        $query = DB::table('program_permissions')
                    ->where('user_id', '=', $uuid)
                    ->pluck('role_id')
                    ->all();
                    
        return $query;
    }

    public function get_sys_perm(){
        $query = DB::table('sys_permission')
                    ->pluck('sys_permission_id')            
                    ->all();

        return $query;
    }

    public function get_module_name(){
        $query = DB::table('sys_modules')
                    ->pluck('modules')
                    ->all();
            
        return $query;
    }

    public function get_program_with_role($uuid){
        $query = DB::table('program_permissions as pp')
                        ->select('p.title', 'p.program_id', 'p.description','pp.role_id', 'r.role',)
                        ->leftJoin('roles as r', 'pp.role_id', '=', 'r.role_id')
                        ->leftJoin('programs as p', 'pp.program_id', '=', 'p.program_id')
                        ->where('pp.user_id', '=', $uuid)
                        ->get();
        
        $data = [];
        
        foreach($query as $key => $v){
            $data[$v->program_id] = $this->get_permission($v->role_id);
        }

        return $data;
    }

    public function get_permission($role_id){
        $query = DB::table('sys_access_matrix as sam')
                    ->select('sm.sys_module_id', 'sm.module', 'sam.role_id','sam.sys_permission_id', 'sp.permission')
                    ->leftJoin('sys_modules as sm', 'sam.sys_module_id', '=', 'sm.sys_module_id')
                    ->leftJoin('sys_permission as sp', 'sam.sys_permission_id', '=', 'sp.sys_permission_id')
                    ->where('sam.status', '=', 1)
                    ->where('sam.role_id', $role_id)
                    ->get();

        $data = [];
        foreach($query as $key => $v){
            $data[$v->module]['Permission'][] = $v->sys_permission_id;
            $data[$v->module]['Module_name'] = $v->module;
        }

        return $data;
    }

    public function check_module_path($role_id){
        $query = DB::table('sys_access_matrix as sam')
                        ->select('sm.sys_module_id', 'sm.module', 'sm.routes', 'sam.role_id','sam.sys_permission_id', 'sp.permission')
                        ->leftJoin('sys_modules as sm', 'sam.sys_module_id', '=', 'sm.sys_module_id')
                        ->leftJoin('sys_permission as sp', 'sam.sys_permission_id', '=', 'sp.sys_permission_id')
                        ->where('sm.routes', '=', \Request::route()->getName())
                        ->where('sam.status', '=', 1)
                        ->whereIn('sam.role_id', $role_id)
                        ->get();
        
        return $query;
    }

    public function get_main_module($role_id){
        $query = DB::table('sys_modules as sm')  
                            ->whereIn('sm.sys_module_id',function($query) use($role_id){
                                $query->select('sm.sys_module_id') 
                                ->from('sys_modules as sm')
                                ->join('sys_access_matrix as sam','sm.sys_module_id','sam.sys_module_id')                                                                                                                                        
                                ->where('sm.status', '1')
                                ->whereIn('role_id', $role_id)                   
                                ->get();
                            })                                  
                            // ->groupBy('sm.parent_module_id')        
                            ->groupBy(DB::raw('ifnull(parent_module_id,sys_module_id)'))                                                       
                            ->get();

        return $query;
    }

    public function get_parent_module(){
        $query = DB::table('sys_modules')
                        ->where('has_sub','1')    
                        ->where('status','1')                                    
                        ->get();

        return $query;
    }

    public function get_sub_module($role_id){
        $query = DB::table('sys_modules as sm')                            
                                ->whereIn('sm.sys_module_id',function($query) use($role_id){
                                        $query->select('sm.sys_module_id') 
                                        ->from('sys_modules as sm')
                                        ->join('sys_access_matrix as sam','sm.sys_module_id','sam.sys_module_id')                            
                                        ->where('sm.status', '1')
                                        ->whereIn('role_id', $role_id)  
                                        ->whereNotNull('parent_module_id')                                                                                          
                                        ->get();
                                })
                                ->orderBy('sequence')
                                ->get();      

        return $query;
    }

    // Supplier for peter
    // public function get_supplier($uuid){
    //     $query = DB::table('supplier as s')
    //                     ->select('s.supplier_id', 's.supplier_name', 'p.description', 'sp.program_id', 's.reg', 's.bank_account_name', 
    //                              'g.iso_prv', 'u.user_id', 'u.first_name', 'u.middle_name', 'u.last_name', 'u.ext_name',  
    //                             )
    //                     ->leftJoin('supplier_programs as sp', 's.supplier_id', '=', 'sp.supplier_id')
    //                     ->leftJoin('programs as p', 'sp.program_id', '=', 'p.program_id')
    //                     ->leftJoin('users as u', 's.supplier_id', '=', 'u.user_id')
    //                     ->leftJoin('geo_map as g', 'u.geo_code', '=', 'g.geo_code')
    //                     ->where('s.supplier_id', '=', $uuid)
    //                     ->get();

    //     return $query;
    // }

    // DEFAULT PROGRAM MUST BE LATEST PROGRAM CREATED 
    // public function get_default_program(){
    //     $query = DB::table('programs')
    //                     ->limit(1)
    //                     ->orderBy('date_created', 'desc')
    //                     ->get();

    //     return $query;
    // }
}
