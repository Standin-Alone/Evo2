<?php

namespace App\Modules\SupplierMainBranch\Http\Controllers;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\GlobalNotificationModel;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\SupplierMainBranch\Models\SupplierMainBranch;

class SupplierMainBranchController extends Controller
{

    public function __construct(Request $request)
    {
        $this->QueryMainBranch = new SupplierMainBranch;
    }

    public function index()
    {
        return view("SupplierMainBranch::index");
    }

    public function Viewing_MainBranch(Request $request)    
    {
        $session_reg_code = sprintf("%02d", session('reg_code'));
        $session_prog_id =  session('program_id');
        if ($request->ajax()) {
                $getData = DB::table('supplier_group as sg')
                    ->select(DB::raw("sg.supplier_group_id,sg.group_name,sg.address,sg.date_created"))
                    // ->where('sg.reg_code',$session_reg_code)   
                    // ->where('sg.program_id',$session_prog_id) 
                    ->get();            
            return Datatables::of($getData)->make(true);    
        } 
    }

    public function Viewing_SubMainBranch(Request $request)    
    {
        $session_reg_code = sprintf("%02d", session('reg_code'));
        $session_prog_id =  session('program_id');
        $group_id = $request->group_id;
        if ($request->ajax()) {
                $getData = DB::table('supplier as s')
                    ->select(DB::raw("s.supplier_name,s.address,s.email,s.contact"))
                    ->where('s.supplier_group_id',$group_id)
                    // ->where('sg.reg_code',$session_reg_code)   
                    // ->where('sg.program_id',$session_prog_id) 
                    ->get();            
            return Datatables::of($getData)->make(true);    
        } 
    }

    public function Inserting_MainBranch(Request $request)    
    {
        return $this->QueryMainBranch->insert_MainBranch($request->group_name,$request->address);        
    }

    public function Updating_MainBranch(Request $request)    
    {
        return $this->QueryMainBranch->Update_MainBranch($request->group_id,$request->group_name,$request->address);     
    }

    public function Removing_MainBranch(Request $request)    
    {
        return $this->QueryMainBranch->Remove_MainBranch($request->group_id);     
    }
}
