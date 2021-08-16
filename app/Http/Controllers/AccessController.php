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


        $get_main_modules = db::table('sys_modules as sm')  
                            
                            ->whereIn('sm.sys_module_id',function($query){
                                    $query->select('sm.sys_module_id') 
                                    ->from('sys_modules as sm')
                                    ->join('sys_access_matrix as sam','sm.sys_module_id','sam.sys_module_id')                                                                                                                                        
                                    ->where('sm.status', 1)
                                    ->where('role_id',5)                                      
                                    ->get();
                            })                                    
                            ->groupBy('sm.parent_module_id')                                                               
                            ->get();
                            
        $get_parent_modules = db::table('sys_modules')
                                    ->where('has_sub',1)    
                                    ->where('status',1)                                    
                                    ->get();
        $get_sub_modules = db::table('sys_modules as sm')                            
                            ->whereIn('sm.sys_module_id',function($query){
                                    $query->select('sm.sys_module_id') 
                                    ->from('sys_modules as sm')
                                    ->join('sys_access_matrix as sam','sm.sys_module_id','sam.sys_module_id')                            
                                    ->where('sm.status', 1)
                                    ->where('role_id',5)  
                                    ->whereNotNull('parent_module_id')                                                                                          
                                    ->get();
                            })                        
                               
                            ->get();    
          
        
        session(['role'=>'RFO Program Staff']);
        session(['main_modules'=>$get_main_modules]);
        session(['parent_modules'=>$get_parent_modules]);
        session(['sub_modules'=>$get_sub_modules]);


     
   
        
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
