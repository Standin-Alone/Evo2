<?php

namespace App\Modules\SubmitDisbursement\Http\Controllers;

use ZipArchive;
use App\Exports;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Exports\DownloadPayoutExcel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\GlobalNotificationModel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\GenerateDisbursementExcel;
use Illuminate\Contracts\Filesystem\Filesystem;
use App\Modules\SubmitPayouts\Models\SubmitPayouts;

class SubmitDisbursementController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!empty(session('uuid'))){
            return view("SubmitDisbursement::index");
        }
        else{
            return redirect('/login');
        }       
    }

    public function getSubmitDisbursementList(Request $request){
        try {
            $session_reg_code = sprintf("%02d", session('reg_code'));
            $getData = "";
            if ($request->ajax()) {
                $getData = DB::table('kyc_profiles as kyc')
                    ->select(DB::raw("kyc.date_uploaded,kyc.approved_date,kyc.approved_batch_seq,sum(".session("disburse_amount").") as amount,format(count(kyc.kyc_id),0) as total_records,kyc.fintech_provider"))
                    ->leftjoin('dbp_batch as dbp','dbp.dbp_batch_id','kyc.dbp_batch_id')
                    ->where('kyc.reg_code',$session_reg_code) 
                    ->where('kyc.approved_by_d','1')
                    ->whereRaw('kyc.dbp_batch_id is null')
                    ->where('kyc.isremove',"0")
                    ->groupBy('kyc.approved_batch_seq')
                    ->orderBy('kyc.date_uploaded','DESC')
                    ->get();            
                return Datatables::of($getData)
                ->make(true);
            }
        } catch (\Throwable $th) {
            return dd($th);
        }        
    }

    public function getFinalSubmitDisbursementList(Request $request){
        try {
            $session_reg_code = sprintf("%02d", session('reg_code'));
            $getData = "";
            if ($request->ajax()) {
                $getData = DB::table('dbp_batch as dbp')
                    ->select(DB::raw("dbp.created_at,dbp.folder_file_name,sum(dbp.total_amount) as total_amount,sum(dbp.total_records) as total_records"))
                    ->where('dbp.reg_code',$session_reg_code)
                    ->where('dbp.isfinalsubmit',"0")
                    ->groupBy('dbp.folder_file_name')
                    ->get();            
                return Datatables::of($getData)
                ->make(true);
            }
        } catch (\Throwable $th) {
            return dd($th);
        }
    }

    public function getSubmitDisbursementProvinceList(){
        try {
            $session_reg_code = sprintf("%02d", session('reg_code'));
            $getProvince = DB::table('geo_map as geo')
            ->select(DB::raw("geo.prov_code,geo.prov_name"))
            ->where('geo.reg_code',$session_reg_code)
            ->groupBy('geo.prov_code')
            ->get();
            return response()->json($getProvince);
        } catch (\Throwable $th) {
            return dd($th);
        }        
    }

    public function generateSubmitDisbursement(Request $request){
            $reg_shortname = ""; 
            $program = session('Default_Program_shortname'); 
            $iso_prv = ""; 

            $getRegShortName = DB::table('kyc_profiles as kyc')
                ->select(DB::raw("g.reg_shortname,g.iso_prv,SUBSTRING(kyc.approved_batch_seq,7,4) as program"))
                ->leftJoin('geo_map as g', function($join)
                    {
                        $join->on('g.reg_code', '=', 'kyc.reg_code');
                        $join->on('g.prov_code','=','kyc.prov_code');
                    })
                ->where('approved_batch_seq',$request->batch_id)
                ->take(1)
                ->get();            
            foreach ($getRegShortName as $key => $row) {
                $reg_shortname = str_replace(" ","",$row->reg_shortname);           
                $iso_prv = $row->iso_prv;
            } 
            $base_path = "dbp_files";
            $program_year = now('GMT+8')->format('Y'); 
            $getDBP_file_Series = "001"; 
            $get_fileseries = DB::table('dbp_batch')->select(DB::raw("MAX(RIGHT(concat('000',right(file_series,3)+1),3)) as file_series"))
                ->where('prv_code',$iso_prv)
                ->groupBy('file_series')
                ->orderBy('file_series', 'DESC')
                ->take(1)
                ->get();
            foreach ($get_fileseries as $key => $series) {
                $getDBP_file_Series = $series->file_series;
            } 
            $folder_File_Name = substr($request->batch_id,0,-3);
            session()->put('folder_File_Name',$folder_File_Name);
            session()->put('dbp_iso_prv',$iso_prv);
            session()->put('dbp_reg_shortname',$reg_shortname); 
            session()->put('getDBP_file_Series',$getDBP_file_Series); 
            $text_content = "";
            $text_nextline = "\n";
            $total_records = 0;
            $totalamt = 0;
            $batch_id = "";
            $program_File_Name = $request->batch_id;
            $fund_id = "";
            $disburse_amount = session("disburse_amount").'.00';

            $get_batchdetails = DB::table('kyc_profiles as kyc')
                ->select(DB::raw("kyc.kyc_id,kyc.account_number,kyc.rsbsa_no,kyc.fintech_provider,TRIM(CONCAT(ucase(ifnull(kyc.first_name,'')),' ', ucase(ifnull(kyc.ext_name,'')))) as firstname,TRIM(CONCAT(ifnull(kyc.street_purok,''),' ', ifnull(kyc.barangay,''))) as street, ucase(kyc.last_name) as last_name,ucase(kyc.first_name) as first_name,ucase(kyc.ext_name) as ext_name,ucase(kyc.middle_name) as middle_name,kyc.street_purok,kyc.barangay,kyc.account_number as acc_num,kyc.municipality,kyc.province,kyc.mobile_no,kyc.remarks,ad.da_shortname,ad.address,ad.city_municipality,ad.province as da_province,kyc.approved_batch_seq,kyc.fund_id,
                kyc.reg_code,kyc.prov_code,kyc.mun_code,kyc.bgy_code"))
                ->leftjoin('da_addresses as ad','ad.reg_code','kyc.reg_code')
                ->where('kyc.approved_batch_seq',$request->batch_id)
                ->where('kyc.approved_by_d','1')
                ->whereRaw('kyc.dbp_batch_id is null')
                ->where('kyc.isremove',"0")
                ->get();
            foreach ($get_batchdetails as $key => $row) {
                $middle_name = $row->middle_name;
                if($middle_name == "" || $middle_name == null ){
                    $middle_name = "NMN";
                }
                $batch_id=$row->approved_batch_seq;  
                $fund_id = $row->fund_id;
                $fintech = $row->fintech_provider;
                if($fintech == "UMSI"){
                    $fintech ="USSC";
                }
                DB::table('excel_export')->insert([
                    'trans_id'=> Uuid::uuid4(),
                    'funding_currency'=>"PHP",
                    'today_date'=>Carbon::now('GMT+8'),
                    'imc'=>"IMC",
                    'ewallet_number'=>$row->acc_num,
                    'amount' =>$disburse_amount,
                    'rsbsa_no'=>$row->rsbsa_no,
                    'fintech'=>$row->fintech_provider,
                    'last_name'=>$row->last_name,
                    'first_name'=>$row->firstname,
                    'middle_name'=>$middle_name,
                    'street_purok'=>$row->street,
                    'city_municipality'=>$row->municipality,
                    'province'=>$row->province,
                    'beneficiary_telnum'=>"", // TEMPORARY NO VALUE
                    'contact_num'=>$row->mobile_no,
                    'message'=>"",
                    'remitter_name_1'=>$row->da_shortname,
                    'remitter_name_2'=>"DEPT", // DEFAULT VALUE
                    'remitter_name_3'=>"OF", // DEFAULT NO VALUE
                    'remitter_pro_id'=>"DARRFA", // DEFAULT VALUE
                    'beneficiary_id'=>"", // TEMPORARY NO VALUE
                    'remitter_address_1'=>$row->address,
                    'remitter_city'=>$row->city_municipality,
                    'remitter_province'=>$row->da_province,
                    'created_date'=>Carbon::now('GMT+8'),
                    'created_by_id'=>session('user_id'),
                    'created_by_fullname'=>session('user_fullname'),
                    'file_name'=>$program_File_Name,
                    'folder_file_name'=>$folder_File_Name,
                    'approved_batch_seq'=>$row->approved_batch_seq,
                    'kyc_id'=>$row->kyc_id,
                    'program_id'=>session('Default_Program_Id'),
                    'fund_id'=>$row->fund_id,
                    'reg_code'=>$row->reg_code,
                    'prov_code'=>$row->prov_code,
                    'mun_code'=>$row->mun_code,
                    'bgy_code'=>$row->bgy_code,
                ]);
                
                if($key == 0){
                    $text_content = 'PHP'.'|'.Carbon::parse(Carbon::now('GMT+8'))->format('m/d/Y').'|'.'IMC'.'|'.$row->account_number.'|'.$disburse_amount.'|'.$row->rsbsa_no.'|'.
                    $row->fintech_provider.'|'.$row->firstname.'|'.$middle_name.'|'.$row->last_name.'|'.$row->street.'|'.$row->municipality.'|'.
                    $row->province.'|'.''.'|'.$row->mobile_no.'||'.$row->da_shortname.'|'.'DEPT'.'|'.
                    'OF'.'|'.'DARRFA'.'|'.''.'|'.$row->address.'|'.$row->city_municipality.'|'.$row->da_province;
                }else{
                    $text_content = $text_content.$text_nextline.'PHP'.'|'.Carbon::parse(Carbon::now('GMT+8'))->format('m/d/Y').'|'.'IMC'.'|'.$row->account_number.'|'.$disburse_amount.'|'.$row->rsbsa_no.'|'.
                    $row->fintech_provider.'|'.$row->firstname.'|'.$middle_name.'|'.$row->last_name.'|'.$row->street.'|'.$row->municipality.'|'.
                    $row->province.'|'.''.'|'.$row->mobile_no.'||'.$row->da_shortname.'|'.'DEPT'.'|'.
                    'OF'.'|'.'DARRFA'.'|'.''.'|'.$row->address.'|'.$row->city_municipality.'|'.$row->da_province;
                } 
                $total_records += 1;
                $totalamt += $disburse_amount;
            }        
            session()->put('program_File_Name',$program_File_Name); 
            if (! File::exists($base_path)) {
                File::makeDirectory($base_path, $mode = 0775, true, true);
                if (! File::exists($base_path.'/'.$program)) {
                    File::makeDirectory($base_path.'/'.$program, $mode = 0775, true, true);
                    if (! File::exists($base_path.'/'.$program.'/'.$program_year)) {
                        File::makeDirectory($base_path.'/'.$program.'/'.$program_year, $mode = 0775, true, true);
                        if (! File::exists($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname)) {
                            File::makeDirectory($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname, $mode = 0775, true, true);
                            if (! File::exists($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname.'/'.$iso_prv)) {
                                File::makeDirectory($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname.'/'.$iso_prv, $mode = 0775, true, true);
                            }
                        }
                    }
                }            
            }else{
                if (! File::exists($base_path.'/'.$program)) {
                    File::makeDirectory($base_path.'/'.$program, $mode = 0775, true, true);
                    if (! File::exists($base_path.'/'.$program.'/'.$program_year)) {
                        File::makeDirectory($base_path.'/'.$program.'/'.$program_year, $mode = 0775, true, true);
                        if (! File::exists($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname)) {
                            File::makeDirectory($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname, $mode = 0775, true, true);
                            if (! File::exists($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname.'/'.$iso_prv)) {
                                File::makeDirectory($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname.'/'.$iso_prv, $mode = 0775, true, true);
                            }
                        }
                    }
                }
                else{                
                    if (! File::exists($base_path.'/'.$program.'/'.$program_year)) {
                        File::makeDirectory($base_path.'/'.$program.'/'.$program_year, $mode = 0775, true, true);
                        if (! File::exists($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname)) {
                            File::makeDirectory($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname, $mode = 0775, true, true);
                            if (! File::exists($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname.'/'.$iso_prv)) {
                                File::makeDirectory($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname.'/'.$iso_prv, $mode = 0775, true, true);
                            }
                        }
                    }else{
                        if (! File::exists($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname)) {
                            File::makeDirectory($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname, $mode = 0775, true, true);
                            if (! File::exists($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname.'/'.$iso_prv)) {
                                File::makeDirectory($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname.'/'.$iso_prv, $mode = 0775, true, true);
                            }
                        }else{
                            if (! File::exists($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname.'/'.$iso_prv)) {
                                File::makeDirectory($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname.'/'.$iso_prv, $mode = 0775, true, true);
                            }else{
                                if (! File::exists($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname.'/'.$iso_prv.'/'.$folder_File_Name)) {
                                    File::makeDirectory($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname.'/'.$iso_prv.'/'.$folder_File_Name, $mode = 0775, true, true);
                                }
                            }
                        }
                    }
                }
            }
            $path = '/'.$program.'/'.$program_year.'/'.$reg_shortname.'/'.$iso_prv.'/'.$folder_File_Name;
            session()->put('path',$base_path.'/'.$path);
        try {
            return $this->GenerateTextFile($iso_prv,$reg_shortname,$program_File_Name,$folder_File_Name,$text_content,$total_records,$batch_id,$totalamt,$fund_id);   

        } catch (\Throwable $th) {
            DB::table('excel_export')
            ->where('file_name', $program_File_Name)
            ->delete();  
            DB::table('dbp_batch')
            ->where('name', $program_File_Name)
            ->delete();
            return "failed";
        }
    }

    function GenerateTextFile($header_prov_code,$reg_shortname,$program_File_Name,$folder_file_name,$text_content,$total_records,$batch_id,$totalamt,$fund_id){      
        try {
            $row_value = [];
            $text_content = $text_content;
            $text_nextline = "\n";
            $header_prefix = "HDR";     
            $header_program = "";       
            $header_date = Carbon::now('GMT+8')->format('Ymd');
            $header_separator = "_";
            $header_prov_code = $header_prov_code;
            $reg_shortname = $reg_shortname;
            $program_File_Name = $program_File_Name;
            $folder_file_name = $folder_file_name;
            $total_records = $total_records;
            $batch_id = $batch_id;
            $totalamt = $totalamt;

            $footer_prefix = "FTR";
            $footer_totalamt = 0;
            $get_totalamt = DB::table('excel_export')->select(DB::raw("replace(format(sum(amount),2),',','') as amount"))->get();
            foreach ($get_totalamt as $key => $amt) {
                $footer_totalamt = $amt->amount;
            }
            $footer_numrows = 0; 
            $get_numrows = DB::table('excel_export')
                ->select(DB::raw("(CASE WHEN ifnull(count(trans_id),'') = '' THEN '0001' ELSE concat('',(RIGHT(concat('0000',right(count(trans_id),4)),4))) END) as numrows"))
                ->where('file_name',$program_File_Name)
                ->get();
            foreach ($get_numrows as $key => $cnt) {
                $footer_numrows = $cnt->numrows;
            }             
            $getDBP_file_Series = session('getDBP_file_Series');
            $dbp_batch_id = Uuid::uuid4();            
            $footer_text=$footer_prefix.$footer_numrows.$footer_totalamt;
            $lastline = $footer_prefix.$footer_numrows.'s';
            
            DB::table('dbp_batch')->insert([
                'dbp_batch_id'=>$dbp_batch_id,
                'date'=>Carbon::now('GMT+8'),            
                'name'=>session('program_File_Name'),  
                'total_amount'=>$totalamt,
                'status'=>1,
                'created_at'=>Carbon::now('GMT+8'),
                'created_by_id'=>session('user_id'),
                'created_by_fullname'=>session('user_fullname'), 
                'prv_code'=>session('dbp_iso_prv'),  
                'reg_shortname'=>$reg_shortname,      
                'file_series'=>$getDBP_file_Series,  
                'program_id'=>session('Default_Program_Id'), 
                'total_records'=>$total_records,
                'folder_file_name'=>session('folder_File_Name'), 
                'reg_code'=>session('reg_code'),
                'approved_batch_seq'=>$batch_id,         
            ]);

            DB::table('kyc_profiles')
            ->where('approved_batch_seq', $batch_id)
            ->update(['dbp_batch_id' => $dbp_batch_id]); 

            DB::table('dbp_batch')
            ->where('dbp_batch_id', $dbp_batch_id)
            ->update(['approver_id' => session('user_id'),'approver_fullname' => session('user_fullname'), 'date_approved' => Carbon::now('GMT+8')]);

            $path = session('Default_Program_shortname').'/'.now('GMT+8')->format('Y').'/'.$reg_shortname.'/'.$header_prov_code.'/'.$folder_file_name;
            Storage::disk('dbp_files')->put($path.'/'.$program_File_Name.'.txt', $text_content); 
            
            $dbp_batch_name = 0; 
            $get_dbp_batch_name = DB::table('dbp_batch')
                ->select(DB::raw("approved_batch_seq"))
                ->where('dbp_batch_id',$dbp_batch_id)
                ->get();
            foreach ($get_dbp_batch_name as $key => $name) {
                $dbp_batch_name = $name->approved_batch_seq;
            } 
        } catch (\Throwable $th) {
            DB::table('excel_export')
            ->where('file_name', $program_File_Name)
            ->delete();  
            DB::table('dbp_batch')
            ->where('name', $program_File_Name)
            ->delete();
            return "failed";
        }
    }

    public function generateFinalSubmitDisbursement(Request $request){
        try {
            $zip = new \ZipArchive();
            $reg_shortname = ""; 
            $program = session('Default_Program_shortname'); 
            $iso_prv = ""; 
            $program_year = now('GMT+8')->format('Y');  
            $folder_File_Name = $request->folder_file_name; 
            $zip_file = "";
            $getprovDetails = DB::table('dbp_batch as dbp')
                ->where('folder_file_name',$request->folder_file_name)
                ->take(1)
                ->groupBy('dbp.folder_file_name')
                ->orderBy('dbp.folder_file_name','DESC')
                ->get();
            foreach ($getprovDetails as $key => $row) {
                $reg_shortname = $row->reg_shortname;
                $iso_prv = $row->prv_code;
                $zip_file = $row->folder_file_name.'.zip';
            }   
            $path = 'dbp_files'.'/'.$program.'/'.$program_year.'/'.$reg_shortname.'/'.$iso_prv.'/'.$folder_File_Name;
            if( File::exists($path.'/'.$zip_file)){
                return "failed";
            }
            $zip->open($path.'/'.$zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
            foreach ($files as $name => $file)
            {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();  
                    $relativePath = substr($file, strlen($path) + 1);
                    $zip->addFile($filePath, $relativePath);                
                }            
            }
            $zip->close();
            DB::table('dbp_batch')
            ->where('folder_file_name', $folder_File_Name)
            ->update(['isfinalsubmit' => 1]); 
            return response()->download($path.'/'.$zip_file);
        } catch (\Throwable $th) {
            return dd($th);
        }
    }

    public function getgeneratedSubmitDisbursementlist(Request $request){
        try {
            if ($request->ajax()) {
                $getData = DB::table('dbp_batch as dbp')
                ->select(DB::raw("dbp.created_at,dbp.folder_file_name,ifnull(dbp.approver_id,'') as approver_id,
                (case when isfinalsubmit='1' then folder_file_name else dbp.name end) as name,
                (case when isfinalsubmit='1' then '(.zip)' else '(.txt)' end) as filetype,dbp.isdownloaded,dbp.isdownloadedxls,dbp.isdownloadeddocx,
                ifnull((select sum(ee.amount) from excel_export as ee where ee.approved_batch_seq=dbp.approved_batch_seq),0) as total_amount,
                ifnull((select count(ee.kyc_id) from excel_export as ee where ee.approved_batch_seq=dbp.approved_batch_seq),0) as total_records,
                isfinalsubmit,(case when isfinalsubmit='1' then folder_file_name else name end) as groupfile"))
                ->where('dbp.reg_code',session('reg_code'))
                ->whereRaw('dbp.approver_id is not null')
                ->groupBy("groupfile")
                ->get();
                return Datatables::of($getData)
                    ->make(true);
            }
        } catch (\Throwable $th) {
            return dd($th);
        }        
    }

    public function downloadSubmitDisbursementExcelFile(Request $request){
        $folder_filename = $request->folderfilename; 
        $file_type = $request->filetype;
        $filename = $request->excelfilename;        
        session()->put('Downloaded_folder_filename',$folder_filename);
        session()->put('Downloaded_filename',$filename);
        $reg_shortname = ""; 
        $file_year = "";
        $program = session('Default_Program_shortname'); 
        $iso_prv = ""; 
        $folder_File_Name = "";
        $File_Name = "";
        $file_extention = ""; 
        $dbp_batch_id = "";
        $dbp_batch_name = "";
        if($file_type == "(.zip)"){
            $getRegCodePrv = DB::table('dbp_batch as dbp')
            ->select(DB::raw("dbp.dbp_batch_id,dbp.reg_shortname,dbp.prv_code,dbp.name,SUBSTRING(dbp.name,1,4) as program,dbp.folder_file_name,year(dbp.date) date_year"))       
            ->where('dbp.folder_file_name','=',$folder_filename)
            ->where('dbp.isfinalsubmit','1')
            ->groupBy('dbp.name')
            ->take(1)
            ->get(); 
            foreach ($getRegCodePrv as $key => $getVal) {
                $folder_File_Name = $getVal->folder_file_name;
                $File_Name = $getVal->name;
                $iso_prv = $getVal->prv_code;
                $reg_shortname = $getVal->reg_shortname; 
                $program = $getVal->program; 
                $dbp_batch_id = $getVal->dbp_batch_id;
                $file_year = $getVal->date_year;
            } 
            $file_extention = ".zip";    
            $path = 'dbp_files'.'/'.$program.'/'.$file_year.'/'.$reg_shortname.'/'.$iso_prv.'/'.$folder_File_Name;
            return response()->download($path.'/'.$folder_File_Name.$file_extention);   

        }else if($file_type == "(.txt)"){
            $getRegCodePrv1 = DB::table('dbp_batch as dbp')
            ->select(DB::raw("dbp.dbp_batch_id,dbp.reg_shortname,dbp.prv_code,dbp.name,SUBSTRING(dbp.name,1,4) as program,dbp.folder_file_name,year(dbp.date) date_year"))       
            ->where('dbp.name','=',$filename)
            ->groupBy('dbp.name')
            ->orderBy('dbp.name','DESC')
            ->take(1)
            ->get();
            foreach ($getRegCodePrv1 as $key => $getVal1) {
                $folder_File_Name = $getVal1->folder_file_name;
                $File_Name = $getVal1->name;
                $iso_prv = $getVal1->prv_code;
                $reg_shortname = $getVal1->reg_shortname; 
                $dbp_batch_id = $getVal1->dbp_batch_id;
                $file_year = $getVal1->date_year;
            } 
            DB::table('dl_textfile_logs')->insert([
                'trans_id'=> Uuid::uuid4(),
                'dbp_batch_id'=> $dbp_batch_id,
                'date_downloaded'=> Carbon::now('GMT+8'),
                'downloaded_by_id'=> session('user_id'),
                'downloaded_by_fullname'=> session('user_fullname'),
            ]);
            DB::table('dbp_batch')
            ->where('dbp_batch_id', $dbp_batch_id)
            ->update(['isdownloaded' => 1,'date_downloaded'=>Carbon::now('GMT+8')]);             
            $file_extention = ".txt";
            $path = 'dbp_files'.'/'.$program.'/'.$file_year.'/'.$reg_shortname.'/'.$iso_prv.'/'.$folder_File_Name;
            return response()->download($path.'/'.$File_Name.$file_extention); 

        }else if($file_type == "(.docx)"){
            $getRegCodePrv2 = DB::table('dbp_batch as dbp')
            ->select(DB::raw("dbp.dbp_batch_id,dbp.approved_batch_seq"))       
            ->where('dbp.name','=',$filename)
            ->groupBy('dbp.name')
            ->orderBy('dbp.name','DESC')
            ->take(1)
            ->get();
            foreach ($getRegCodePrv2 as $key => $getrow) {
                $dbp_batch_name = $getrow->approved_batch_seq;
                $dbp_batch_id = $getrow->dbp_batch_id;
            } 
            DB::table('dbp_batch')
            ->where('dbp_batch_id', $dbp_batch_id)
            ->update(['isdownloadeddocx' => 1,'date_downloadeddocx'=>Carbon::now('GMT+8')]); 
            return response()->download('uploads'.'/'.'fintech-for-signature'.'/'.'SPTI'.'/'.$dbp_batch_name.'-FOR-SIGNATURE.docx');

        }else if($file_type == "(.xls)"){
            $file_extention = ".xls"; 
            $path2 = 'dbp_files'.'/'.$program.'/'.$file_year.'/'.$reg_shortname.'/'.$iso_prv.'/'.$folder_File_Name;
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
            ->update(['isdownloadedxls' => 1,'date_downloadedxls'=>Carbon::now('GMT+8')]); 
            return (new GenerateDisbursementExcel)->download($filename.$file_extention, \Maatwebsite\Excel\Excel::XLSX);
        }                     
    }

    public function getgeneratedDisburseHistory(Request $request){
        if ($request->ajax()) {
            $getData = DB::table('dbp_batch as dbp')
            ->select(DB::raw("dbp.dbp_batch_id,dbp.created_at,dbp.folder_file_name,
            (case when isfinalsubmit='1' then folder_file_name else dbp.name end) as name,
            dbp.total_amount,dbp.total_records,isfinalsubmit,(case when isfinalsubmit='1' then folder_file_name else name end) as groupfile"))
            ->where('dbp.reg_code',session('reg_code'))
             ->where('dbp.isfinalsubmit','1')
            ->groupBy("groupfile")
            ->get();
            return Datatables::of($getData)->make(true);
        }        
    }

    public function generate_signature_form_spti($dbp_batch_id,$dbp_batch_name){
        $get_profiles = db::table("kyc_profiles")->where('dbp_batch_id',$dbp_batch_id)->where('isremove',0)->orderBy('last_name')->get();
        $count_profiles = count($get_profiles) ;
        $compute_page = ($count_profiles / 4);
        $total_page = ($compute_page %  2 ) == 0 ? $compute_page : $compute_page  +1;
        $my_template = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('SPTI-Signature-Card-Format.docx'));
        $my_template->cloneBlock('CLONEBLOCK', $total_page); 
        $i = 0;        
        while($i <= $count_profiles){
            if($i < $count_profiles){                    
                $name_counter = $my_template->setValue('full_name','${full_name'.$i.'}',2);
                $rsbsa_counter =$my_template->setValue('rsbsa_no','${rsbsa_no'.$i.'}',2);  
            }
            $i++;
        }        
        foreach ($get_profiles as $key => $value) {                                
            echo $key.$value->first_name.' '.$value->last_name.'<br>';            
            $my_template->setValue('full_name'.$key,$value->first_name.' '.$value->middle_name.' '.$value->last_name,2);
            $my_template->setValue('rsbsa_no'.$key,$value->rsbsa_no,2);
            $my_template->setValue('dbp_batch_name',$dbp_batch_name);                              
        }
        $my_template->setValue('full_name','');
        $my_template->setValue('rsbsa_no', '');  
        $my_template->saveAs('uploads/fintech-for-signature/SPTI/'.$dbp_batch_name.'-FOR-SIGNATURE.docx');
    }
}
