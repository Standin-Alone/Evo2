<?php

namespace App\Modules\ProgramItems\Http\Controllers;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\GlobalNotificationModel;
use App\Modules\ProgramItems\Models\ProgramItems;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProgramItemsController extends Controller
{

    public function __construct(Request $request)
    {
        $this->QueryProgramItems = new ProgramItems();
    }

    public function index()
    {
        return view("ProgramItems::index");
    }

    public function getProgramItemsList(Request $request)
    {   
        $session_reg_code = sprintf("%02d", session('reg_code'));     
        if ($request->ajax()) {
            $data = DB::table('supplier as s')
                    ->select(DB::raw("s.supplier_id,s.supplier_name,sg.group_name as supplier_group,s.address,
                        AES_DECRYPT(s.bank_account_no,'".session('private_secret_key')."') as bank_account_no,
                        s.email,s.contact"))
                    ->leftJoin('supplier_group as sg','sg.supplier_group_id','s.supplier_group_id')                    
                    // ->where('reg_code',$session_reg_code)
                    ->get();
            return Datatables::of($data)->make(true);
        }
    }

    public function getProgramItemsDetails(Request $request){
        $supplier_id = $request->supplier_id;
        return $this->QueryProgramItems->get_ProgramItemsDetails($supplier_id);
    }

    public function getProgramRegion(){       
        return $this->QueryProgramItems->get_ProgramRegion();
    }

    public function getProgramProvince(Request $request){
        return $this->QueryProgramItems->get_ProgramProvince();
    }

}
