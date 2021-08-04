<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class AccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('auth.login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function signIn()
    {


        
        $modules = [];

        $get_modules = db::table('sys_modules as sm')
                            ->select('sm.module','sm.routes','sm.has_sub','sm.sys_module_id','sm.parent_module_id')                            
                            ->join('sys_access_matrix as sam','sm.sys_module_id','sam.sys_module_id')                            
                          
                            
                            ->where('sm.status', 1)
                            ->where('role_id',5)                            
                            ->groupBy('module')
                            ->get();




        foreach($get_modules as $item){

            if(!is_null($item->parent_module_id)){
                
                
                $get_parent = db::table('sys_modules')->where('sys_module_id',$item->parent_module_id)->first();    
                $get_module = db::table('sys_modules')->where('sys_module_id',$item->sys_module_id)->get();    
                
                array_push($modules,[ "parent_module_name"=> $get_parent->module,"sub_modules" => $get_module, "has_sub" => 1]);                
                
            }else{

                $get_no_parent = db::table('sys_modules')->where('sys_module_id',$item->sys_module_id)->first();   
                array_push($modules,[ "parent_module_name"=> $get_no_parent->module,"route" => $get_no_parent->routes, "has_sub" => 0]);                
            }
        }

        $result = collect($modules);
    

    
        // echo json_encode(array_unique(array_merge($modules,$modules),SORT_REGULAR ));
        echo json_encode($result);
        session(['role'=>'RFO Program Staff']);
        session(['modules'=>$result]);


     
        
        
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
