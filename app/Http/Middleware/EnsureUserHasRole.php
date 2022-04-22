<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnsureUserHasRole
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

        $role_id = session()->get('role_no_sets');

        $check_module_path = DB::table('sys_access_matrix as sam')
                                    ->select('sm.sys_module_id', 'sm.module', 'sm.routes', 'sam.role_id','sam.sys_permission_id', 'sp.permission')
                                    ->leftJoin('sys_modules as sm', 'sam.sys_module_id', '=', 'sm.sys_module_id')
                                    ->leftJoin('sys_permission as sp', 'sam.sys_permission_id', '=', 'sp.sys_permission_id')
                                    ->where('sm.routes', '=', \Request::route()->getName())
                                    ->where('sam.status', '=', 1)
                                    ->whereIn('sam.role_id', $role_id)
                                    ->get();
        $list_of_roles = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23];
        $list_of_sys_permission_id = [1,2,3,4,5,6];

        if($check_module_path != '[]'){
            foreach($check_module_path as $cmp){
                if((in_array($cmp->role_id ,$list_of_roles)) == true){
                    if( (in_array($cmp->sys_permission_id ,$list_of_sys_permission_id)) == true){
                        return $next($request);
                        
                    }else{
                        return redirect()->route('error_page.index');
                    }
                }else{
                    return redirect()->route('error_page.index');
                }
            }
        }else{
            return redirect()->route('error_page.index');
        }

    }
}
