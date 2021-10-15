<?php

namespace App\Modules\DisbursementHeadApproval\Http\Controllers;

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
use App\Exports\GenerateDisbursementExcel;
use App\Models\GlobalNotificationModel;

class DisbursementHeadApprovalController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!empty(session('user_id'))) {
            return view("DisbursementHeadApproval::index");
        }else{
            return redirect('/login');
        }
    }

    public function getHeadApprovalList(Request $request){
        try {
            if ($request->ajax()) {
                $getData = DB::table('dbp_batch as dbp')
                ->select(DB::raw("dbp.dbp_batch_id,dbp.created_at,dbp.folder_file_name,
                (case when isfinalsubmit='1' then folder_file_name else dbp.name end) as name,
                (case when isfinalsubmit='1' then '(.zip)' else '(.xls)' end) as filetype,dbp.isdownloadedxls_r,dbp.date_downloadedxls,
                dbp.total_amount,dbp.total_records,isfinalsubmit,(case when isfinalsubmit='1' then folder_file_name else name end) as groupfile"))
                ->where('dbp.reg_code',session('reg_code'))
                ->where('dbp.approver_id','')
                ->groupBy("groupfile")
                ->get();
                return Datatables::of($getData)->make(true);
            }
        } catch (\Throwable $th) {
            return dd($th);
        }
    } 

    public function ApproveHeadApproval(Request $request)
    {
        try {           

            $folder_file_name = explode(",",$request->folder_file_name);
                for($i = 0; $i < count($folder_file_name); ++$i) {
                    // UPDATE BATCH FOR DBP ID  
                    DB::table('dbp_batch')
                    ->where('folder_file_name', $folder_file_name[$i])
                    ->update(['approver_id' => session('user_id'),'approver_fullname' => session('user_fullname'), 'date_approved' => Carbon::now('GMT+8')]);

                    $role = session('role_name_sets');
                    $role_name = $role[0];
                    $session_reg_code = sprintf("%02d", session('reg_code'));
                    $message = 'Regional Executive Director approved generated DBP batch textfile '.$folder_file_name[$i].'. ';
                    $message = $message.'for more details just click the provided link to direct access the system.';

                    $global_model = new GlobalNotificationModel;        
                    return $global_model->send_email($role_name,$session_reg_code,$message);  
                }
                

            } catch (\Throwable $th) {
                return dd($th);
        }
    }

    public function getDBPapproveddHistoryList(Request $request){
        try {
            if ($request->ajax()) {
                $getData = DB::table('dbp_batch as dbp')
                ->select(DB::raw("dbp.dbp_batch_id,dbp.created_at,dbp.folder_file_name,
                (case when isfinalsubmit='1' then folder_file_name else dbp.name end) as name,
                dbp.total_amount,dbp.total_records,isfinalsubmit,(case when isfinalsubmit='1' then folder_file_name else name end) as groupfile"))
                ->where('dbp.approver_id','<>','')
                ->groupBy("groupfile")
                ->get();
                return Datatables::of($getData)->make(true);
            }
        } catch (\Throwable $th) {
            return dd($th);
        }
        
    }

    public function validateHeadApprovalPin(Request $request){        
        try {
            session()->put('Downloaded_folder_filename',$request->folder_filename);
            session()->put('Downloaded_filename',$request->filename);
            return session()->put('Downloaded_file_type',$request->filetype);
        } catch (\Throwable $th) {
            return dd($th);
        }    
    }

    public function downloadDBPBatch(Request $request){
        try {            
            $folder_filename = session('Downloaded_folder_filename'); 
            $file_type = session('Downloaded_file_type');
            $filename = session('Downloaded_filename');

            $reg_shortname = ""; 
            $program = session('Default_Program_shortname'); 
            $iso_prv = ""; 

            $folder_File_Name = "";
            $File_Name = "";
            $file_extention = ""; 

            $dbp_batch_id = "";

            $file_extention = ".xls"; 
                $path2 = 'dbp_files'.'/'.$program.'/'.now('GMT+8')->format('Y').'/'.$reg_shortname.'/'.$iso_prv.'/'.$folder_File_Name;
                $getRegCodePrv2 = DB::table('dbp_batch as dbp')
                ->where('dbp.name','=',$filename)
                ->groupBy('dbp.name')
                ->orderBy('dbp.name','DESC')
                ->take(1)
                ->get(); 

                foreach ($getRegCodePrv2 as $key => $getVal2) {
                    $dbp_batch_id = $getVal2->dbp_batch_id;
                } 

                DB::table('dbp_batch')
                ->where('dbp_batch_id', $dbp_batch_id)
                ->update(['isdownloadedxls_r' => 1,'date_downloadedxls_r'=>Carbon::now('GMT+8')]); 
                return (new GenerateDisbursementExcel)->download($filename.$file_extention, \Maatwebsite\Excel\Excel::XLSX);
               
                
        } catch (\Throwable $th) {
            return back()->with('failed','File does not exist! Please settle final submit.');
        }  
    }
}
