<?php

namespace App\Modules\SubmittedPayoutFiles\Http\Controllers;

use ZipArchive;
use App\Exports;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Exports\GeneratePayoutExcelFile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Filesystem\Filesystem;
use App\Modules\SubmittedPayoutFiles\Models\SubmittedPayoutFiles;

class SubmittedPayoutFilesController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if (!empty(session('uuid'))) {
            return view("SubmittedPayoutFiles::index");
        }else{
            return redirect('/login');
        }
    }

    public function getSubmittedPayoutFilesList(Request $request){
        if ($request->ajax()) {
            $getData = DB::table('payout_gfi_details as pgd')
                ->select(DB::raw("pgd.payout_trans_code,pgd.payout_id,pgd.batch_id,DATE_FORMAT(pgd.approved_approver_date, '%M %d, %Y %H:%i: %p') as transac_date,pgb.application_number,pgb.description,
                    ifnull((select sum(distinct amount) from payout_gif_batch as p where supplier_id=s.supplier_id),0) as amount,pgb.issubmitted,p.description as program,
                    count(distinct transaction_id) as cnt_vouchers,s.supplier_name,count(distinct s.supplier_id) as merchant_count,s.reg_desc,pgd.textfile_gen_date"))
                ->leftJoin('payout_gif_batch as pgb', 'pgd.batch_id', '=', 'pgb.batch_id')
                ->leftJoin('supplier as s', 's.supplier_id', '=', 'pgb.supplier_id')
                ->leftJoin('supplier_programs as sp', 'pgb.supplier_id', '=', 'sp.supplier_id')
                ->leftJoin('programs as p', 'p.program_id', '=', 'pgb.program_id')
                // ->where('p.program_id',session('Default_Program_Id'))
                ->where('pgb.issubmitted',"1")
                ->whereNotNull('pgd.approved_by_approver')
                ->whereNotNull('pgd.payout_trans_code')
                ->where('pgd.iscomplete',0)
                ->groupBy("pgd.payout_trans_code")
                ->get();
            return Datatables::of($getData)->make(true);
        }
    }

    public function completeSubmittedPayoutFiles(Request $request)
    {
        DB::table('voucher_transaction as vt')
            ->leftJoin('payout_gfi_details as pgd','pgd.transaction_id','vt.transaction_id')        
            ->where('pgd.payout_trans_code',  $request->selectedid)
            ->update(['vt.payout' => '1', 'vt.payout_date' => Carbon::now('GMT+8')]);

        return DB::table('payout_gfi_details')        
            ->where('payout_trans_code',  $request->selectedid)
            ->update(['iscomplete' => 1, 'date_completed' => Carbon::now('GMT+8'), 'completed_by_id' => session('uuid')]); 
    }

    public function getCompletedPayoutTextfilesHistory(Request $request){
        if ($request->ajax()) {
            $getData = DB::table('payout_gfi_details as pgd')
                ->select(DB::raw("pgd.payout_trans_code,pgd.payout_id,pgd.batch_id,DATE_FORMAT(pgd.approved_approver_date, '%M %d, %Y %H:%i: %p') as transac_date,pgb.application_number,pgb.description,
                    ifnull((select sum(distinct amount) from payout_gif_batch as p where supplier_id=s.supplier_id),0) as amount,pgb.issubmitted,p.description as program,
                    count(distinct transaction_id) as cnt_vouchers,s.supplier_name,count(distinct s.supplier_id) as merchant_count,s.reg_desc,pgd.textfile_gen_date"))
                ->leftJoin('payout_gif_batch as pgb', 'pgd.batch_id', '=', 'pgb.batch_id')
                ->leftJoin('supplier as s', 's.supplier_id', '=', 'pgb.supplier_id')
                ->leftJoin('supplier_programs as sp', 'pgb.supplier_id', '=', 'sp.supplier_id')
                ->leftJoin('programs as p', 'p.program_id', '=', 'pgb.program_id')
                // ->where('p.program_id',session('Default_Program_Id'))
                ->where('pgb.issubmitted',"1")
                ->where('pgd.approved_by_approver','<>',NULL)
                ->where('pgd.payout_trans_code','<>',NULL)
                ->where('pgd.iscomplete',1)
                ->groupBy("pgd.payout_trans_code")
                ->get();
            return Datatables::of($getData)->make(true);
        }
    }

    public function getTotal_Completed_Payout_Textfiles(){
        $reg_code = sprintf("%02d", session('reg_code'));
        $viewData = DB::table('vw_transaction_totals')
            ->where('program_id',session('Default_Program_Id'))
            ->where(function ($query1) use ($reg_code){
                if($reg_code !='13'){
                    $query1->where('reg',$reg_code);
                }
            })
            ->get();

        return response()->json($viewData); 
    }
    
}
