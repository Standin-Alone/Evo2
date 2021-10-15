<?php

namespace App\Modules\DBPapproval\Http\Controllers;

use App\Exports;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Exports\DownloadPayoutExcel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DBPapprovalController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!empty(session('user_id'))) {
            return view("DBPapproval::index");
        }else{
            return redirect('/login');
        }
    }

    public function getDBPapprovalList(Request $request){
        if ($request->ajax()) {
            $getData = DB::table('dbp_batch as dbp')
            ->where('dbp.program_id',session('Default_Program_Id'))
            ->where('approver_id','')
            ->groupBy("dbp.name")
            ->get();
            return Datatables::of($getData)
                ->addColumn('action', function($row){
                    // return '<a href='.route("download.DBPBatchDownload").'?file_name='.$row->name.' data-textfilename="'.$row->name.'" class="btn btn-xs btn-outline-info btnDownloadTextFile"><span class="fa fa-cloud-download-alt"></span><i class="fas fa-spinner fa-spin '.$row->name.' pull-left m-r-10" style="display: none;"></i> Download</a>';
                    return '<a href="javascript:;" data-textfilename="'.$row->name.'" class="btn btn-xs btn-outline-info btnDownloadTextFile"><span class="fa fa-cloud-download-alt"></span><i class="fas fa-spinner fa-spin '.$row->name.' pull-left m-r-10" style="display: none;"></i> Download</a>';
                
                })
                ->addColumn('checkbox', function($row){
                    return '
                    <div class="checkbox checkbox-css">
                        <input type="checkbox" id="cssCheckbox1" data-selecteddbpbatchid="'.$row->dbp_batch_id.'" data-selecteddbpbatchtotalamt="'.$row->total_amount.'" class="selecteddbpbatch" />
                        <label for="cssCheckbox1"></label>
                    </div>';                
                })
                ->rawColumns(['action','checkbox'])
                ->make(true);
        }
    }

    public function downloadDBPBatch(Request $request){
        $filename = session('Download_filename');
        $getRegCodePrv = DB::table('payout_export')
            ->where('file_name','=',$filename)
            ->get();
            $reg_shortname = "";
            $iso_prv = "";
            foreach ($getRegCodePrv as $key => $getVal) {
                $reg_shortname = $getVal->reg_shortname;
                $iso_prv = $getVal->iso_prv;
            }   
            $reg_shortname = str_replace(" ","",$reg_shortname);

        $path = 'dbp_files'.'/'.session('Default_Program_shortname').'/'.now('GMT+8')->format('Y').'/'.$reg_shortname.'/'.$iso_prv;
        // return Excel::download(new DownloadPayoutExcel, $path.'/'.$request->file_name.'.xls','dbp_files');
        return response()->download($path.'/'.$filename.'.xls');

    }

    public function ApproveDBPapproval(Request $request)
    {
        $batchid = explode(",",$request->batchid);
        for($i = 0; $i < count($batchid); ++$i) {
            // UPDATE BATCH FOR DBP ID  
            DB::table('dbp_batch')
            ->where('dbp_batch_id', $batchid[$i])
            ->update(['approver_id' => session('user_id'),'approver_fullname' => session('user_fullname'), 'date_approved' => Carbon::now('GMT+8')]);
        }
    }

    public function getDBPapproveddHistoryList(Request $request){
        if ($request->ajax()) {
            $getData = DB::table('dbp_batch as dbp')
            ->select(DB::raw("dbp.created_at,dbp.name as file_name,sum(dbp.total_amount) as total_amount, total_records"))
            ->where('approver_id','<>','')
            ->groupBy('dbp.name')
            ->get();
            return Datatables::of($getData)->make(true);
        }
    }

    public function validateDBPApprovalPin(Request $request){        
        $validate_email = DB::table('users')
        ->where('email','=',$request->email)
        ->exists();
        if($validate_email){
            $get_email = DB::table('users')
            ->select(DB::raw("password"))
            ->where('email','=',$request->email)
            ->where('isusepin',"1")
            ->get();
            if(!empty($get_email)){
                foreach ($get_email as $key => $row) {
                    if (Hash::check($request->password, $row->password)) {
                        return session()->put('Download_filename',$request->filename);   
                    }else{
                        return "INVALID";
                    }
                }            
            }else{
                return "NO_EXIST";
            }
        }else{
            return "NO_EXIST";
        }
        
    }
}
