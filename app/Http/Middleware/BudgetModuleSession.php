<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BudgetModuleSession
{
    /**
    * Custom parameters.
    *
    * @var \Symfony\Component\HttpFoundation\ParameterBag
    *
    * @api
    */
   public $attributes;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $module_name)
    {
        $group_programs = session()->get('progs');
        // dd($group_programs);
        dd($module_name);
        $data = [];

        foreach($group_programs as $key => $val){
            foreach($val as $k => $v){
                if($k == $module_name){
                    if(in_array(2, $v['Permission'])){
                        array_push($data, $key);
                    }
                }
            }
        }

        session()->put(['programs_ids'=> $data]);

        // $request->attributes->add(['programs_ids' => $data]);

        // $request->get('programs_ids');

        return $next($request);
    }
}
