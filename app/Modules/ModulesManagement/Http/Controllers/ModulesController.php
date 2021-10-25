<?php

namespace App\Modules\ModulesManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Artisan;
use Carbon\Carbon;
use ArtemSchander\L5Modular\Facades\L5Modular;
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
            $has_sub       = request('has_sub');

            $check_module =  L5Modular::exists(trim($module_name[0]));

            $check_db = db::table("sys_modules")->where('module',trim($module_name[0]))->get();
            

            
            
            if($has_sub != 1){

            

                if($check_db->isEmpty()){
                    db::table('sys_modules')
                    ->insert(['module'=>trim($module_name[0]),'routes'=>$route]);
                }

                return 'true';                
            }else{
                $get_last_id = 0;
                $get_main_module = '';
                foreach($module_name as $key => $item){
            
                    if($key == 0){
                        $check_mainmodule = db::table("sys_modules")->where('module',trim($item))->get();
                        if($check_mainmodule->isEmpty()){
                            
                        $get_last_id =  db::table('sys_modules')
                                            ->insertGetId([
                                                "module" => $item,
                                                "has_sub" => 1
                                            
                                            ]);
                                            
                            $get_main_module = $item;
                            
                        }
                        
                    }else{
                        
                        $get_last_id = db::table("sys_modules")->where('module',trim($get_main_module))->first()->sys_module_id;
                        // dd($get_last_id);
                        $subtract_key = $key - 1;
                        foreach($route as $route_key => $route_item){
                            
                            if($route_key == $subtract_key){                                
                                $check_submodule = db::table("sys_modules")->where('module',trim($item))->get();
                             
                                if($check_submodule->isEmpty()){
                                    
                                db::table('sys_modules')
                                    ->insert([
                                        "module"           => $item,
                                        "routes"           => $route_item,
                                        "parent_module_id" => $get_last_id
                                    ]);    
                                    
                                }

                                                            
                            }
                        }
                        
                    }
                }
                return 'true';
       
            }
         
            
        }catch(\Exception $e){
            dd($e);
        }
    }   

    public function store_sub_modules(){
        try{
            $module_name = request('module_name');
            $route       = request('route');
            $parent_module_id       = request('parent_module_id');

            $check_module =  L5Modular::exists(trim($module_name));
            db::table('sys_modules')
                ->insert([
                    "module"           => $module_name,
                    "routes"           => $route,
                    "parent_module_id" => $parent_module_id
                ]);        

            if(!$check_module){
                // Artisan::call("make:module",["name" => trim($module_name)]);
            }   
            
        }catch(\Exception $e){

        }
    }
    
    // update data
    public function update($id){
        $module_name = request('module_name');
        $route       = request('route');
        $get_id = request('id');
        
        db::table('sys_modules')
            ->where('sys_module_id',$get_id)
            ->update(['module'=>$module_name,'routes'=>$route]);
    }

    // show record to datatable
    public function show($id){
        $get_record = db::table('sys_modules')->whereNull('parent_module_id')->get();
        foreach($get_record as $item){
            if($item->has_sub == 1){
                $item->sub_modules =  db::table('sys_modules')->where('parent_module_id',$item->sys_module_id)->get();
            }else{
                $item->sub_modules = [];
            }
        }
        return datatables($get_record)->toJson();    
    }

    // show sub modules
    public function show_sub_modules($id){
        $get_record = db::table('sys_modules')->where('parent_module_id',$id)->get();
        return datatables($get_record)->toJson();    
    }

    public function destroy($id){
        $status = request('status');
        
        

        db::table('sys_modules')
            ->where('sys_module_id',$id)
            ->update(['status'=>$status == 1 ? '0' : '1']);
        
        
    }




}
