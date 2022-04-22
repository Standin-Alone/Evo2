<?php

namespace App\Modules\VoucherListing\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Modules\VoucherListing\Models\VoucherList;
use App\Modules\VoucherListing\Models\BatchList;
use Yajra\DataTables\DataTables;

class VoucherListController extends Controller
{
    public function voucher_view($batch_no)
    {


        $claimed = DB::table('voucher')
                   ->where('batch_code', '=', $batch_no)
                   ->whereIn('voucher_status', ['FULLY CLAIMED', 'PARTIALLY CLAIMED']);
        $claimed_ttl = $claimed->count();

        $unclaimed = DB::table('voucher')
                   ->where('batch_code', '=', $batch_no)
                   ->whereIn('voucher_status', ['NOT YET CLAIMED']);
        $unclaimed_ttl = $unclaimed->count();

        return view('VoucherListing::voucher-gen',['batch_no'=>$batch_no,'claimed_ttl'=>$claimed_ttl,'unclaimed_ttl'=>$unclaimed_ttl]);




    }

    public function vouchererr_view($batch_no)
    {

        $claimed = DB::table('voucher')
                   ->where('batch_code', '=', $batch_no)
                   ->whereIn('voucher_status', ['WITH ERROR']);
        $error_ttl = $claimed->count();

        return view('VoucherListing::voucher-gen-err',['batch_no'=>$batch_no,'error_ttl'=>$error_ttl]);

    }

    public function batch_view()
    {

        $usr_reg = session('region');
        $claimed = DB::table('voucher')->where('reg', '=', $usr_reg)->whereIn('voucher_status', ['FULLY CLAIMED', 'PARTIALLY CLAIMED']);
        $claimed_ttl = $claimed->count();
        $unclaimed = DB::table('voucher')->where('reg', '=', $usr_reg)->where('voucher_status', '=', 'NOT YET CLAIMED');
        $unclaimed_ttl = $unclaimed->count();
        $cancelled = DB::table('voucher')->where('reg', '=', $usr_reg)->where('voucher_status', '=', 'CANCELLED');
        $cancelled_ttl = $cancelled->count();
        $ttl_vch = $claimed_ttl+$unclaimed_ttl;

        return view('VoucherListing::batch-gen',['claimed_ttl'=>$claimed_ttl,'unclaimed_ttl'=>$unclaimed_ttl,'ttl_vch'=>$ttl_vch,'ttl_cncl'=>$cancelled_ttl]);

    }

    public function getVouchers(Request $request,$batch_no)
    {

        if ($request->ajax()) {
            $data = DB::table('voucher')->where('batch_code', '=', $batch_no)
                    ->whereIn('voucher_status', ['NOT YET CLAIMED', 'FULLY CLAIMED', 'PARTIALLY CLAIMED', 'CANCELLED'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = "<a href='voucherprint/".$row->reference_no."' class='edit btn btn-success btn-sm'>Print</a> <a href='voucherdelete/".$row->reference_no."' onclick='return confirm("."'Are you sure you want to cancel this voucher?'".")' class='delete btn btn-danger btn-sm'>Cancel</a>";
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getVouchersErr(Request $request,$batch_no)
    {

        if ($request->ajax()) {
            $data = DB::table('voucher')->where('batch_code', '=', $batch_no)
                    ->whereIn('voucher_status', ['WITH ERROR'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = "<a href='voucherprint/".$row->reference_no."' class='edit btn btn-success btn-sm'>Print</a> <a href='voucherdelete/".$row->reference_no."' onclick='return confirm("."'Are you sure you want to cancel this voucher?'".")' class='delete btn btn-danger btn-sm'>Cancel</a>";
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getBatch(Request $request_batch)
    {

        if ($request_batch->ajax()) {
            $usr_reg = session('region');
            $data_batch = DB::table('vw_batch_lists')->where('batch_reg', '=', $usr_reg)->get();

            return Datatables::of($data_batch)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = "<a href='voucherprint/".$row->batch_code."' class='edit btn bg-lime btn-sm'>Print</a> <a href='voucherdelete/".$row->batch_code."' onclick='return confirm("."'Are you sure you want to cancel this voucher?'".")' class='delete btn  bg-secondary btn-sm'>Cancel</a>";
                    return $actionBtn;
                })

                ->rawColumns(['action'])
                ->make(true);
        }
        
    }


    public function getVoucherPrintInd($ref_no)
    {

        $ref_search = DB::table('voucher')->where('reference_no', $ref_no)->first();
        return view('VoucherListing::voucher-print-ind',['ref_no'=>$ref_search->reference_no,'name_f'=>$ref_search->first_name,'name_m'=>$ref_search->middle_name,'name_l'=>$ref_search->last_name,'name_ext'=>$ref_search->ext_name,'amount'=>$ref_search->amount]);
    }

    public function getVoucherPrintBatch($ref_no)
    {
        $ref_search = DB::table('voucher')->get();
        return view('VoucherListing::voucher-print-batch',['ref_search'=>$ref_search]);
    }

    public function getVoucherErrPrintBatch($batch_no)
    {
        $ref_search = DB::table('voucher')
                    ->where('batch_code', '=', $batch_no)
                    ->whereIn('voucher_status', ['WITH ERROR'])
                    ->get();
        return view('VoucherListing::voucher-error-print-batch',['ref_search'=>$ref_search]);
    }

    public function getVoucherDeleteInd($ref_no)
    {

        DB::table('voucher')->where('reference_no', $ref_no)->delete(); 
        return redirect()->route('voucher-generation');
    
    }


    public function DeleteVouch(Request $request)
    {

        $request = request()->all();
        $g_batch_code = $request['batch_no'];
        $g_ref_no = $request['ref_no'];
        
        DB::table('voucher')
        ->where('reference_no', $g_ref_no) 
        ->limit(1)
        ->update(array('voucher_status' => 'CANCELLED'));
        
        return redirect('vouchergen/'.$g_batch_code);
    }

    public function RestoreVouch(Request $request)
    {

        $request = request()->all();
        $g_batch_code = $request['batch_no'];
        $g_ref_no = $request['ref_no'];
        
        DB::table('voucher')
        ->where('reference_no', $g_ref_no) 
        ->limit(1)
        ->update(array('voucher_status' => 'NOT YET CLAIMED'));
        
        return redirect('vouchergen/'.$g_batch_code);
    }

    public function getVoucherDeleteBatch($g_batch_code)
    {

        DB::table('voucher')
        ->where('batch_code', $g_batch_code) 
        ->whereIn('voucher_status', ['NOT YET CLAIMED'])
        ->update(array('voucher_status' => 'CANCELLED'));

        return redirect('vouchergen/'.$g_batch_code);
    
    }

}