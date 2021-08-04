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

            

            if($has_sub != 1){
                Artisan::call("make:module",["name" => $module_name]);
                db::table('sys_modules')
                ->insert(['module'=>$module_name,'routes'=>$route]);
            }else{
                $get_last_id = 0;
                foreach($module_name as $key => $item){

                    if($key == 0){
                        $get_last_id =  db::table('sys_modules')
                                            ->insertGetId([
                                                "module" => $item,
                                                "has_sub" => 1
                                            
                                            ]);
                    }else{
                        foreach($route as $route_key => $route_item){

                            if($route_key == $key - 1 ){
                                db::table('sys_modules')
                                    ->insert([
                                        "module"           => $item,
                                        "routes"           => $route_item,
                                        "parent_module_id" => $get_last_id
                                    ]);
                                
                                Artisan::call("make:module",["name" => $item]);
                            }
                        }
                    }
                }
                
            }
            

            
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
        
        L5Modular::disable('Modulename');

        db::table('sys_modules')
            ->where('sys_module_id',$id)
            ->update(['status'=>$status == 1 ? '0' : '1']);
        
        
    }

}
