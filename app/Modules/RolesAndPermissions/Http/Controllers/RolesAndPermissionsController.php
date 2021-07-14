<?php

namespace App\Modules\RolesAndPermissions\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\RolesAndPermissions\Models\RolesAndPermissions;
use DB;
class RolesAndPermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   

        $get_permissions = db::table('sys_permission')->get();
        
        
        return view('RolesAndPermissions::index',compact('get_permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //  
        $record = array();
        $get_roles = DB::table('roles')->get();        

        foreach($get_roles as $item){

            $get_modules = db::table('sys_modules as sm')
                                ->select('module')
                                ->join('sys_access_matrix as sam','sm.sys_module_id','sam.sys_module_id')
                                ->groupBy('module')
                                ->where('sam.role_id',$item->role_id)->get();
                                
                array_push($record,['role_id'=>$item->role_id,'role'=>$item->role, 'modules'=>$get_modules ]);
        }

        return datatables($record)->toJson();
    }


    public function get_permissions(){
        
        $get_permission = DB::table('sys_permission')->get();
                                

        return $get_permission;
    }

    
    public function get_module_permissions(){
        $id = request('role_id');
        $record = [];
        $get_module_permission = DB::table('roles as r')
                                ->select('module')                                                                
                                ->join('sys_access_matrix as sam','r.role_id','sam.role_id')                                
                                ->join('sys_modules as sm','sm.sys_module_id','sam.sys_module_id')                                
                                ->where('r.role_id',$id)
                                ->groupBy('module')
                                ->get();
        
        foreach($get_module_permission as $key_item => $item){
                $get_permissions_id = DB::table('roles as r')
                                ->select('sp.sys_permission_id as sys_permission_id','permission','status')                                                                                                
                                ->join('sys_access_matrix as sam','r.role_id','sam.role_id')                                
                                ->join('sys_permission as sp','sp.sys_permission_id','sam.sys_permission_id')                                
                                ->join('sys_modules as sm','sm.sys_module_id','sam.sys_module_id')                                
                                ->where('r.role_id',$id)                                
                                ->where('module',$item->module)                                
                                ->get();
                array_push($record,["module"=>$item->module]);
            foreach($get_permissions_id as $key_val => $val){
                $record[$key_item][$val->permission] =  $val->status;
            }
            
            
        }

        
        return datatables($record)->toJson();
    }



    public function select_modules(){
        $id = request('role_id');
        $get_module_matrix = DB::table('roles as r')                                                                
                                ->join('sys_access_matrix as sam','r.role_id','sam.role_id')                                
                                ->join('sys_modules as sm','sm.sys_module_id','sam.sys_module_id')
                                ->where('r.role_id',$id)
                                ->groupBy('module')
                                ->pluck('module');
        $get_modules = db::table('sys_modules')->whereNotIn('module',$get_module_matrix)->get();
        return $get_modules;
    }   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $role_id = request('role_id');
        $module = request('module');
        $id = request('id');
        
        $get_permissions = db::table('sys_permission')->get();
        $get_module = db::table('sys_modules')->where('module',$module)->first();
        foreach($get_permissions as $item){
            $insert_mod_permissions = db::table('sys_access_matrix')->insert(
                                            [
                                                'role_id' => $role_id,
                                                'sys_permission_id' => $item->sys_permission_id,
                                                'sys_module_id' => $get_module->sys_module_id,

                                            ]);
        }
        

        

    }   

    public function set_permissions(){
        $role_id = request('role_id');
        $module = request('module');
        $permission = request('permission');
        $checked = request('checked');
        $access_matrix_model = new RolesAndPermissions();

        $get_permissions = db::table('sys_permission')->where('permission',$permission)->first();
        $get_module = db::table('sys_modules')->where('module',$module)->first();

        if($checked == true){
            $access_matrix_model
                    ->where('role_id',$role_id)
                    ->where('sys_module_id',$get_module->sys_module_id)
                    ->where('sys_permission_id',$get_permissions->sys_permission_id)
                    ->update([
                            'status' => '0'
                    ]);
        }else{
            $access_matrix_model
                    ->where('role_id',$role_id)
                    ->where('sys_module_id',$get_module->sys_module_id)
                    ->where('sys_permission_id',$get_permissions->sys_permission_id)
                    ->update([
                            'status' => '1'
                    ]);
        }
        

    }
}
