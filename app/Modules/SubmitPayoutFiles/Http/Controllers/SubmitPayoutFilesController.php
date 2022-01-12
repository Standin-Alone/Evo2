<?php

namespace App\Modules\SubmitPayoutFiles\Http\Controllers;

use App\Exports;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Exports\GeneratePayoutExcel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use App\Modules\SubmitPayoutFiles\Models\SubmitPayoutFiles;

class SubmitPayoutFilesController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if (!empty(session('uuid'))) {
            return view("SubmitPayoutFiles::index");
        }else{
            return redirect('/login');
        }
    }

    public function getSubmitPayoutFileList(Request $request){
        if ($request->ajax()) {
            $getData = DB::table('dbp_batch as dbp')
            ->where('dbp.program_id',session('Default_Program_Id'))
            ->where('dbp.approver_id','<>','')
            ->where('dbp.dbp_textfile_id','')
            ->groupBy("dbp.name")
            ->get();
            return Datatables::of($getData)
                ->addColumn('action', function($row){
                    return '<a href="javascript:void(0);"  data-selecteddbpfileid="'.$row->dbp_batch_id.'" data-selectedfilename="'.$row->name.'" class="btn btn-xs btn-outline-info btnGenerateKey"><span class="fa fa-file-alt"></span><i class="fas fa-spinner fa-spin '.$row->name.' pull-left m-r-10" style="display: none;"></i> Generate Textfile</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function GeneratePrivateKey(Request $request)
    {
        $row_value = [];
        $text_content = "";
        $text_nextline = "\n";
        $header_prefix = "HDR";     
        $header_program = "";       
        $header_date = Carbon::now('GMT+8')->format('Ymd');
        $header_separator = "_";
        $header_prov_code = "NEC"; // TEMP
        $header_series = "";
        $get_series = DB::table('dbp_batch')
            ->select(DB::raw("(CASE WHEN MAX(RIGHT(concat('000',right(file_series,3)+1),3)) IS NULL THEN '001' ELSE MAX(RIGHT(concat('000',right(file_series,3)+1),3)) END) as payoutseries"))
            ->where('name',$request->file_name)
            ->get();
        foreach ($get_series as $key => $row) {
            $header_series = $row->payoutseries;
        }        
        if($header_series == ""){ 
            $header_series = "001"; 
        }

        $footer_prefix = "FTR";
        $footer_totalamt = 0;
        $get_totalamt = DB::table('payout_export')->select(DB::raw("replace(format(sum(amount),2),',','') as amount"))->get();
        foreach ($get_totalamt as $key => $amt) {
            $footer_totalamt = $amt->amount;
        }
        $footer_totalamt = sprintf("%'.08d\n", $footer_totalamt)."0.00";
        $footer_numrows = 0; 
        $get_numrows = DB::table('payout_export')
            ->select(DB::raw("(CASE WHEN ifnull(count(application_number),'') = '' THEN '0001' ELSE concat('',(RIGHT(concat('0000',right(count(application_number),4)),4))) END) as numrows"))
            ->where('file_name',$request->file_name)
            ->get();
        foreach ($get_numrows as $key => $cnt) {
            $footer_numrows = $cnt->numrows;
        } 
        $footer_text=$footer_prefix.$footer_numrows.$footer_totalamt;
        $lastline = $footer_prefix.$footer_numrows.'s';
        $getData = DB::table('payout_export')
            ->where('file_name',$request->file_name)
            ->get(); 
            $text_content = "";       
            $file_name = "";
            $reg_shortname = "";
            $iso_prv = "";
            foreach ($getData as $key => $value) {
                $reg_shortname = $value->reg_shortname;
                $iso_prv = $value->iso_prv;
                if($key == 0){
                    $header_text = $header_prefix.$value->file_name.$header_separator.$iso_prv.$header_separator.$header_series;
                    $text_content = $header_text.$text_nextline.$value->settlement_currency.'|'.$value->funding_currency.'|'.$value->today_date.'|'.$value->service_code.'|'.
                    $value->application_number.'|'.$value->amount.'|'.$value->account_number.'|'.$value->bank_name.'|'.$value->remarks.'|'.$value->outlet_name.'|'.
                    $value->bene_name1.'|'.$value->bene_name2.'|'.$value->bene_name3.'|'.$value->barangay.'|'.$value->city_municipality.'|'.$value->province.'|'.
                    $value->beneficiary_telnum.'|'.$value->contact_num.'|'.$value->message.'|'.$value->remitter_name_1.'|'.$value->remitter_name_2.'|'.$value->remitter_name_3.'|'.
                    $value->remitter_id.'|'.$value->beneficiary_id.'|'.$value->remitter_address_1.'|'.$value->remitter_address_2.'|'.$value->remitter_address_3.'|'.
                    $value->institution_code.'|'.$value->institution_id_number.'|'.$value->institution_detail1.'|'.$value->institution_detail2.'|'.$value->institution_detail3;
                }else{
                    $text_content = $text_content.$text_nextline.$value->settlement_currency.'|'.$value->funding_currency.'|'.$value->today_date.'|'.$value->service_code.'|'.
                    $value->application_number.'|'.$value->amount.'|'.$value->account_number.'|'.$value->bank_name.'|'.$value->remarks.'|'.$value->outlet_name.'|'.
                    $value->bene_name1.'|'.$value->bene_name2.'|'.$value->bene_name3.'|'.$value->barangay.'|'.$value->city_municipality.'|'.$value->province.'|'.
                    $value->beneficiary_telnum.'|'.$value->contact_num.'|'.$value->message.'|'.$value->remitter_name_1.'|'.$value->remitter_name_2.'|'.$value->remitter_name_3.'|'.
                    $value->remitter_id.'|'.$value->beneficiary_id.'|'.$value->remitter_address_1.'|'.$value->remitter_address_2.'|'.$value->remitter_address_3.'|'.
                    $value->institution_code.'|'.$value->institution_id_number.'|'.$value->institution_detail1.'|'.$value->institution_detail2.'|'.$value->institution_detail3;
                }  
                $file_name = $value->file_name;                
            }

            $reg_shortname = str_replace(" ","",$reg_shortname);

        $text_content = $text_content.$text_nextline.$footer_text; 
        $path = '/'.session('Default_Program_shortname').'/'.now('GMT+8')->format('Y').'/'.$reg_shortname.'/'.$iso_prv;
        if (File::exists('dbp_files'.$path)) {
            Storage::disk('dbp_files')->put($path.'/'.$file_name.'_'.$iso_prv.'_'.$header_series.'.txt', $text_content);
            $private_secret_key = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvh0wGVoAzOcp6NMnLLf+
                                57RvVIlJbNsIKgvGta7to6ei9zEA3zAuWnKtqqVdc9hCssxyUM96RSW/MP+U5N3e
                                AsfAxsOarKnfALxHXwbCZlGTuf6Y/v8WDJU1dIl2pmNdukg06Gfo3lDbn0EdZI4h
                                dmC4oWe/T06+J8Euio/WmgSpzDObia8flvoo8bAohS+WMNgO3HXB42VOTgAhdHdA
                                L1/x9BaxQxtQBJGIeeyud2zzr+dBR7o5TWJ1gK2sEInwzaII9qpXwLxu6kNq4B1M
                                jIF7SO6qg8pBh28/fYXFEoVuk4X3Bs3vproJjeAn7eKksf2fHhMB4FpZiRcawj3c
                                NwIDAQAB' ;

            $textfile_id = Uuid::uuid4();

            // ADD RECORD TO DBP_TEXTFILE TABLE
            DB::table('dbp_textfile')->insert([
                'dbp_textfile_id'=>$textfile_id,
                'transac_date'=>Carbon::now('GMT+8'),            
                'file_name'=>$file_name,  
                'total_amount'=>$footer_totalamt,
                'created_by_id'=>session('user_id'),
                'created_by_fullname'=>session('user_fullname')   
            ]);

            DB::table('dbp_batch')
            ->where('name', $file_name)
            ->update(['dbp_textfile_id' => $textfile_id]);  

            return $this->encrypt_file($text_content, $private_secret_key, $file_name, $footer_totalamt,$reg_shortname,$iso_prv,$header_series,$path);
        }else{
            return "failed";
        }
    }

    public function encrypt_file($message, $encryption_key, $file_name, $footer_totalamt, $reg_shortname,$iso_prv,$header_series,$path){
            $nonceSize = openssl_cipher_iv_length('aes-256-ctr');
            $nonce = openssl_random_pseudo_bytes($nonceSize);
            $ciphertext = openssl_encrypt(
            $message, 
            'aes-256-ctr', 
            $encryption_key,
            OPENSSL_RAW_DATA,
            $nonce
            );             

            return Storage::disk('dbp_files')->put($path.'/'.$file_name.'_'.$iso_prv.'_'.$header_series.'.txt.gpg', $nonce.$ciphertext); 
      }

      public function getGeneratedTextfileHistory(Request $request){
        if ($request->ajax()) {
            $getData = DB::table('dbp_batch as dbp')
            ->where('dbp.program_id',session('Default_Program_Id'))
            ->where('approver_id','<>',null)
            ->where('dbp_textfile_id','<>',null)
            ->groupBy("dbp.name")
            ->get();
            return Datatables::of($getData)->make(true);
        }
      }
}
