<?php

namespace App\Modules\ModulesManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Artisan;

class ModulesController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("ModulesManagement::index");
    }


    // store record
    public function store(){
        try{
            $module_name = request('module_name');
            $route       = request('route');

            Artisan::call("make:module",["name" => $module_name]);
            db::table('sys_modules')
                ->insert(['module'=>$module_name,'routes'=>$route]);

            
        }catch(\Exception $e){
            echo json_encode($e);
        }
    }   
    
    // update data
    public function update($id){
        $module_name = request('module_name');
        $route       = request('route');
        
        db::table('sys_modules')
            ->where('sys_module_id',$id)
            ->update(['module'=>$module_name,'routes'=>$route]);
    }

    // show record to datatable
    public function show($id){
        $get_record = db::table('sys_modules')->get();
        return datatables($get_record)->toJson();    
    }

    public function destroy($id){
        $status = request('status');
        // \ArtemSchander\L5Modular::disable('RolesAndPermissions');
        // // L5Modular::disable('RolesAndPermissions');

        db::table('sys_modules')
            ->where('sys_module_id',$id)
            ->update(['status'=>$status == 1 ? '0' : '1']);
    }
}
