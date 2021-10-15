<?php

namespace App\Modules\SupplierModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\SupplierModule\Models\SupplierModule;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class SupplierModuleController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!empty(session('uuid'))) {
            return view("SupplierModule::index");
        }else{
            return redirect('/login');
        } 
        
    }

    public function updateProgramDefault(Request $request){
        session()->put('Default_Program_Desc',$request->selectedProgramDesc);     
        session()->put('Default_Program_Id',$request->selectedProgramId); 
        return "updated"; 
    }

}
