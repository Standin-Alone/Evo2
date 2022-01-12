<?php

namespace App\Modules\BatchPayout\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\BatchPayout\Models\BatchPayout;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class BatchPayoutController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!empty(session('uuid'))) {
            return view("BatchPayout::index");
        }else{
            return redirect('/login');
        }
    }

    // BATCH PAYOUT DATATABLE CONTENT
    public function getBatchPayoutContent(Request $request)
    {
        
        if ($request->ajax()) {
            // QUERY FOR DATA DISPLAY
            $getData = DB::table('vw_program_payout_batches as pgb')
                ->where('pgb.supplier_id',session('supplier_id'))
                ->where('pgb.program_id',session('Default_Program_Id'))
                ->get();

            return Datatables::of($getData)
                // ADD COLUMN FOR BUTTON ACTION
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $checkbatchusage = DB::table('payout_gfi_details')
                    ->where('batch_id','=',$row->batch_id)
                    ->exists();
                    if($checkbatchusage){
                        $actionBtn = '                             
                                <span class="btn btn-xs" style="pointer-events: none;"><span class="fa fa-edit"></span> Edit</span></span>
                                <span class="btn btn-xs" style="pointer-events: none;"><span class="fa fa-trash"></span> Remove</span></span>';
                    }else{
                        $actionBtn = '
                            <a href="javascript:void(0)" data-editbatchid="'.$row->batch_id.'" data-editapplicationnum="'.$row->application_number.'" data-editdescription="'.$row->description.'" data-editamount="'.$row->amount.'" class="btn btn-xs btn-outline-info btneditbatchpayout"><span class="fa fa-edit"></span><i class="fas fa-spinner fa-spin '.$row->batch_id.' pull-left m-r-10" style="display: none;"></i> Edit</a>                            
                            <a href="javascript:void(0)" data-selectedbatchid="'.$row->batch_id.'" class="btn btn-xs btn-outline-danger btnremovebatchpayout"><i class="fa fa-trash"><i class="fas fa-spinner fa-spin '.$row->batch_id.' pull-left m-r-10" style="display: none;"></i></i> Remove</a>';
                    }                    
                    return $actionBtn;
                })
                // ADD COLUMN FOR GRAND TOTAL
                ->addColumn('grandtotalamount', function($value){
                    $getgrandtotal = DB::table('vw_program_payout_batches as pgb')
                    ->select(DB::raw("sum(pgb.amount) as grantotal"))
                    ->where('pgb.supplier_id',session('supplier_id'))
                    ->where('pgb.program_id',session('Default_Program_Id'))
                    ->get();
                    $grandtotal=0;  
                    foreach ($getgrandtotal as $key => $value) {
                        $grandtotal += $value->grantotal;   
                    }                                     
                    return $grandtotal;
                })
                ->rawColumns(['action','grandtotalamount'])
                ->make(true);
        }
    }

    // SAVING OF BATCH DATA
    public function createBatchPayout(Request $request){
        $CurrentYear = Carbon::now('GMT+8')->format('Y');
        $CurrentProgram = session('Default_Program_shortname');
        $AppNumSeries = "";
        $getData = DB::table('payout_gif_batch as pgb')
                ->select(DB::raw("(CASE WHEN MAX(RIGHT(concat('0000000000',right(application_number,10)+1),10)) IS NULL THEN '0000000001'
                ELSE MAX(RIGHT(concat('0000000000',right(application_number,10)+1),10)) END) as appnumseries"))
                ->get();
            foreach ($getData as $key => $value) {
                $AppNumSeries = 'DA'.$CurrentProgram.$CurrentYear.$value->appnumseries;
            }

        $query = DB::table('payout_gif_batch')->insert([
            'batch_id'=>Uuid::uuid4(),
            'application_number'=>$AppNumSeries,
            'bank_name'=>session('Supplier_Bank_name'),
            'supplier_id'=>session('supplier_id'),
            'amount'=>0,
            'transac_date'=>Carbon::now('GMT+8'),
            'description'=>$request->desc,
            'program_id'=>$request->program_id
        ]);
    }

    // UPDATING OF SELECTED BATCH
    public function updateBatchPayout(Request $request){
        return DB::table('payout_gif_batch')
                ->where('batch_id', $request->batch_id)
                ->update(['description' => $request->desc]);
    }

    // REMOVING SELECTED BATCH
    public function removeBatchPayout(Request $request){
        DB::table('payout_gif_batch')->where('batch_id', $request->batch_id)->delete();

        DB::table('voucher_transaction')
            ->where('batch_id', $request->batch_id)
            ->update(['batch_id' => '']);

        return DB::table('payout_gif_batch')
            ->where('batch_id', $request->batch_id)
            ->update(['issubmitted' => 0]);
    }
}
