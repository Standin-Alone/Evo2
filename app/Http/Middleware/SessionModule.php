<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionModule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $group_programs = session()->get('progs');
        $role_id = session()->get('role_no_sets');
        $action = [];

        $perm = [];

        $check_module_path = DB::table('sys_access_matrix as sam')
                                    ->select('sm.sys_module_id', 'sm.module', 'sm.routes', 'sam.role_id','sam.sys_permission_id', 'sp.permission')
                                    ->leftJoin('sys_modules as sm', 'sam.sys_module_id', '=', 'sm.sys_module_id')
                                    ->leftJoin('sys_permission as sp', 'sam.sys_permission_id', '=', 'sp.sys_permission_id')
                                    ->where('sm.routes', '=', \Request::route()->getName())
                                    ->where('sam.status', '=', 1)
                                    ->whereIn('sam.role_id', $role_id)
                                    ->get();

        foreach($check_module_path as $key => $cmp){
            $perm[$cmp->module][] = $cmp->sys_permission_id; 
            $module = $cmp->module;
        }
       
        session()->put(['check_module_path' => $check_module_path]);

        $data = [];

        foreach($group_programs as $key => $val){
            foreach($val as $k => $v){
                foreach($perm as $module => $perm_id){
                    if($k == $module){
                        if(in_array(1, $v['Permission'])){
                            array_push($data, $key);
                            // Create New Content
                            $action[$key] = 1; 
                        }
                        if(in_array(2, $v['Permission'])){
                            array_push($data, $key);
                            // View Content
                            $action[$key] = 2;
                        }
                        if(in_array(3, $v['Permission'])){
                            array_push($data, $key);
                            // Update Own Content
                            $action[$key] = 3;
                        }
                        if(in_array(4, $v['Permission'])){
                            array_push($data, $key);
                            // Update Any Content
                            $action[$key] = 4;
                        }
                        if(in_array(5, $v['Permission'])){
                            array_push($data, $key);
                            // Delete Own Content
                            $action[$key] = 5;
                        }
                        if(in_array(6, $v['Permission'])){
                            array_push($data, $key);
                            // Delete Any Content
                            $action[$key] = 6;
                        }
                    }
                }
            }
        }

        $request->attributes->add(['programs_ids' => $data]);
        $request->attributes->add(['action' => $action]);

        $request->get('programs_ids');
        $request->get('action');

        session()->put(['programs_ids' => $data]);

        // session()->get('programs_ids');

        return $next($request);
    }
}
