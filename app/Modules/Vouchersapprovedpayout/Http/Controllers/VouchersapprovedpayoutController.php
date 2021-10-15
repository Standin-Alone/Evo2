<?php

namespace App\Modules\Vouchersapprovedpayout\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class VouchersapprovedpayoutController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        if (!empty(session('supplier_id'))) {
            return view("Vouchersapprovedpayout::index");
        }else{
            return redirect('/login');
        }
    }
    
    public function getVoucherApprovedPayoutList(Request $request){        
        if ($request->ajax()) {
            $getData = DB::table('vw_claimed_voucher_items as vt')
                ->where('vt.supplier_id',session('supplier_id'))
                ->where('vt.program_id',session('program_id'))
                ->where('vt.ishold',"0")
                ->where('vt.issubmitted',"1")
                ->where('vt.payout_endorse_approve',"1")
                ->get();
            return Datatables::of($getData)
            ->addColumn('grandtotalamount', function($value){
                $getgrandtotal = DB::table('vw_claimed_voucher_items as vt')
                ->select(DB::raw("sum(total_amount) as grantotal"))
                ->where('vt.supplier_id',session('supplier_id'))
                ->where('vt.program_id',session('program_id'))
                ->where('vt.ishold',"0")
                ->where('vt.issubmitted',"1")
                ->where('vt.payout_endorse_approve',"1")                
                ->get();
                $grandtotal=0;  
                foreach ($getgrandtotal as $key => $value) {
                    $grandtotal += $value->grantotal;   
                }                                     
                return $grandtotal;
            })
            ->rawColumns(['grandtotalamount'])
            ->make(true);
        }
    }
}
