<?php

namespace App\Modules\SubmitPayouts\Http\Controllers;

use App\Exports;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Exports\GeneratePayoutExcel;
use App\Exports\DownloadPayoutExcel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use App\Modules\SubmitPayouts\Models\SubmitPayouts;
use Illuminate\Contracts\Filesystem\Filesystem;

class SubmitPayoutsController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        if (!empty(session('user_id'))) {
            return view("SubmitPayouts::index");
        }else{
            return redirect('/login');
        }
    }

    public function getSubmitPayoutsList(Request $request)
    {
        if ($request->ajax()) {
            $getData = DB::table('payout_gfi_details as pgd')
            ->select(DB::raw("pgd.payout_id,pgd.batch_id,pgd.transac_date,pgb.application_number,pgb.description,
                pgb.amount,pgb.issubmitted,(case when pgb.issubmitted=0 then pgb.amount else 0 end) totalcreated,
                (case when pgb.issubmitted=1 then pgb.amount else 0 end) totalpending"))
            ->leftJoin('payout_gif_batch as pgb', 'pgd.batch_id', '=', 'pgb.batch_id')
            ->where('pgb.program_id',session('Default_Program_Id'))
            ->where('pgb.payout_endorse_approve',"1")
            // ->where('pgb.dbp_batch_id',null)
            ->groupBy("pgd.batch_id")
            ->get();
            return Datatables::of($getData)
                ->addColumn('checkbox', function($row){
                    return '
                    <div class="checkbox checkbox-css">
                        <input type="checkbox" id="cssCheckbox1" data-selectedbatchid="'.$row->batch_id.'" data-selectedbatchamt="'.$row->amount.'" class="selectedbatch" />
                        <label for="cssCheckbox1"></label>
                    </div>';                
                })
                ->rawColumns(['checkbox'])
                ->make(true);
        }
    }

    public function generateSupplierPayoutExcel(Request $request)
    {
        $batchid = explode(",",$request->batchid);        
        $program_Short_Name = session('Default_Program_shortname');
        $program_File_Name = $program_Short_Name.Carbon::now('GMT+8')->format('Ymd');
        for($i = 0; $i < count($batchid); ++$i) {
            $getData = DB::table('payout_gif_batch as pgb')
            ->select(DB::raw("pgb.batch_id,replace(format((round(pgb.amount)),0),',','') as amount,pgb.application_number,pgb.transac_date,s.supplier_id,s.supplier_group_id,s.supplier_name,s.address,(case when s.bank_short_name = 'DBP' then 'DBPACCTPHP' else 'OTHBNKPHP' end) as service_code,
                    ifnull(g.reg_shortname,'') as reg_shortname,ifnull(g.reg_name,'') as reg_name,ifnull(g.prov_name,'') as prov_name,ifnull(g.mun_name,'') as mun_name,ifnull(g.bgy_name,'') as bgy_name,s.bank_account_no,s.bank_account_name,ifnull(u.contact_no,'') as contact_no,ifnull(g.iso_prv,'') as iso_prv"))
            ->leftJoin('supplier as s', 's.supplier_id', '=', 'pgb.supplier_id')
            ->leftJoin('users as u', 'u.user_id', '=', 's.supplier_id')
            ->leftJoin('geo_map as g', 'g.geo_code', '=', 's.geo_code')
            ->where('pgb.batch_id','=',$batchid[$i])            
            ->get();       

            $existDBPBatch = DB::table('dbp_batch')
            ->where('name','=',$program_File_Name)
            ->exists();
            if($existDBPBatch){
                return "failed";
            }
            $reg_shortname = "";
            $iso_prv = "";
            $bank_account_name = "";
            session()->put('excel_file_name',$program_File_Name);
            foreach ($getData as $key => $value) {
                DB::table('payout_export')->insert([
                    'trans_id'=>Uuid::uuid4(),
                    'file_name'=>strtoupper($program_File_Name),  
                    'settlement_currency'=>'PHP',
                    'funding_currency'=>'PHP',
                    'today_date'=>Carbon::now('GMT+8')->format('d/m/Y'),
                    'service_code'=>$value->service_code,
                    'application_number'=>$value->application_number,
                    'amount'=>$value->amount,
                    'account_number'=>$value->bank_account_no,
                    'bank_name'=>$value->bank_account_name,
                    'remarks'=>'',
                    'outlet_name'=>'',
                    'bene_name1'=>strtoupper($value->supplier_name),
                    'bene_name2'=>'.',
                    'bene_name3'=>'',
                    'barangay'=>strtoupper(preg_replace('/'.'[^A-Za-z0-9. -]'.'/', '',$value->bgy_name)),
                    'city_municipality'=>strtoupper(preg_replace('/'.'[^A-Za-z0-9. -]'.'/', '',$value->mun_name)),
                    'province'=>strtoupper(preg_replace('/'.'[^A-Za-z0-9. -]'.'/', '',$value->prov_name)),
                    'beneficiary_telnum'=>'',
                    'contact_num'=>$value->contact_no,
                    'message'=>'',
                    'remitter_name_1'=>'AGRICULTURE-RFF',
                    'remitter_name_2'=>'DEPT',
                    'remitter_name_3'=>'OF',
                    'remitter_id'=>$program_Short_Name,
                    'beneficiary_id'=>'',
                    'remitter_address_1'=>'ELLIPTICAL ROAD DILIMAN',
                    'remitter_address_2'=>'QUEZON CITY',
                    'remitter_address_3'=>'METRO MANILA',
                    'institution_code'=>'',
                    'institution_id_number'=>'',
                    'institution_detail1'=>'',
                    'institution_detail2'=>'',
                    'institution_detail3'=>'',
                    'transac_date'=>Carbon::now('GMT+8'),
                    'transac_by_id'=>'',  
                    'iso_prv'=>$value->iso_prv,  
                    'reg_shortname'=>$value->reg_shortname      
                ]);
                $reg_shortname = $value->reg_shortname;
                $iso_prv = $value->iso_prv;
            }
            
        }
        $reg_shortname = str_replace(" ","",$reg_shortname);
        // session()->put('reg_shortname',$reg_shortname);  
        $base_path = "dbp_files";
        $program = session('Default_Program_shortname');
        $program_year = now('GMT+8')->format('Y');   
        $path = "";     
        // $path = '/'.session('Default_Program_shortname').'/'.now('GMT+8')->format('Y').'/'.$reg_shortname.'/'.$iso_prv;
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
                        }
                    }
                }
            }
        }
        $path = '/'.$program.'/'.$program_year.'/'.$reg_shortname.'/'.$iso_prv;
        // if (! File::exists($path)) {
        //     File::makeDirectory($path, $mode = 0775, true, true);
        // }
        session()->put('program_File_Name',$program_File_Name); 
        try {
            return Excel::store(new GeneratePayoutExcel, $path.'/'.$program_File_Name.'.xls','dbp_files');
        } catch (\Throwable $th) {
            DB::table('payout_export')
            ->where('file_name', session('program_File_Name'))
            ->delete();
           return  DB::table('dbp_batch')
            ->where('name', session('program_File_Name'))
            ->delete();
        }
        
        // return Excel::store(new GeneratePayoutExcel, $program_File_Name.'.xls','dbp_'.$reg_shortname.'_files');
    }    

    public function getSubmitPayoutGeneratedList(Request $request){
        if ($request->ajax()) {
            $getData = DB::table('dbp_batch as dbp')
            ->select(DB::raw("dbp.created_at,dbp.folder_file_name,dbp.total_amount,dbp.total_records"))
            ->where('approver_id','')
            ->groupBy("dbp.name")
            ->get();
            return Datatables::of($getData)
                ->addColumn('action', function($row){
                    return '<a href="javascript:;" data-excelfilename="'.$row->folder_file_name.'" class="btn btn-xs btn-outline-info btnDownloadExcelFile"><span class="fa fa-cloud-download-alt"></span><i class="fas fa-spinner fa-spin '.$row->folder_file_name.' pull-left m-r-10" style="display: none;"></i> Download</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function downloadSubmitPayoutExcelFile(Request $request){
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

    public function getSubmitteddHistoryList(Request $request){
        if ($request->ajax()) {
            $getData = DB::table('payout_export as pe')
            ->select(DB::raw("pe.transac_date,pe.file_name,pe.application_number,pe.amount"))
            ->get();
            return Datatables::of($getData)->make(true);
        }
    }

    public function makeDirectory($path, $mode = 0777, $recursive = false, $force = false)
    {
        if ($force)
        {
            return @mkdir($path, $mode, $recursive);
        }
        else
        {
            return mkdir($path, $mode, $recursive);
        }
    }

    public function validateSubmitPayoutPin(Request $request){
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

    // function encrypt($message, $encryption_key){
    //     // $original_string = "Lets Try This";
    //     // $private_secret_key = '1f4276388ad3214c873428dbef42243f' ;

    //     $key = hex2bin($encryption_key);
    //     $nonceSize = openssl_cipher_iv_length('aes-256-ctr');
    //     $nonce = openssl_random_pseudo_bytes($nonceSize);
    //     $ciphertext = openssl_encrypt(
    //       $message, 
    //       'aes-256-ctr', 
    //       $key,
    //       OPENSSL_RAW_DATA,
    //       $nonce
    //     );
    //     return base64_encode($nonce.$ciphertext);
    //   }

    //   function decrypt($message,$encryption_key){
    //     $key = hex2bin($encryption_key);
    //     $message = base64_decode($message);
    //     $nonceSize = openssl_cipher_iv_length('aes-256-ctr');
    //     $nonce = mb_substr($message, 0, $nonceSize, '8bit');
    //     $ciphertext = mb_substr($message, $nonceSize, null, '8bit');
    //     $plaintext= openssl_decrypt(
    //       $ciphertext, 
    //       'aes-256-ctr', 
    //       $key,
    //       OPENSSL_RAW_DATA,
    //       $nonce
    //     );
    //     return $plaintext;
    //   }

}
