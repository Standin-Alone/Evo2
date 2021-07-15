<?php

namespace App\Modules\ModulesManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
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

    public function store(){

        

    }

    public function show($id){
        $get_record = db::table('sys_modules')->get();
        return datatables($get_record)->toJson();    
    }
}
