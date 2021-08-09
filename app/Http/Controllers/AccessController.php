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


        $get_menu = db::table('sys_modules as sm')                            
                            ->whereIn('sm.sys_module_id',function($query){
                                    $query->select('sm.sys_module_id') 
                                    ->from('sys_modules as sm')
                                    ->join('sys_access_matrix as sam','sm.sys_module_id','sam.sys_module_id')                            
                                    ->where('sm.status', 1)
                                    ->where('role_id',5)                                                        
                                    ->get();
                            })                        
                            ->get();
                    

        foreach($get_menu as  $key => $item){
            if(!is_null($item->parent_module_id)){
                $item->parent_module = db::table('sys_modules')->where('sys_module_id',$item->parent_module_id)->first()->module;
            }
        }


        

        session(['role'=>'RFO Program Staff']);
        session(['unique_modules'=>$get_menu->unique('parent_module')]);
        session(['modules'=>$get_menu]);


     
        
        
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
