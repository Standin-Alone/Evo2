<?php

namespace App\Modules\VoucherListing\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\VoucherListing\Models\Voucher;
use App\Jobs\VUploadProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\VoucherExport;
use Excel;
use DB;
use App\Modules\VoucherListing\Imports\VoucherImport;
use Maatwebsite\Excel\HeadingRowImport;


function generateRandomString($length = 10) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

class VoucherController extends Controller
{
    public function exportIntoExcel()
    {
    	return Excel::download(new VoucherExport,'voucherlist.xlsx');
    }

    public function exportIntoCSV()
    {
    	return Excel::download(new VoucherExport,'voucherlist.csv');
    }

    public function importForm()
    {

        $upload_batch_cde = "IMP".generateRandomString();
        $prog_data = DB::table('programs')->where('program_id', '=', '9fdb5700-6534-4133-8624-f321afb249cf')->get();
        return view('VoucherListing::import-form',['prog_data'=>$prog_data,'batch_code'=>$upload_batch_cde]);
    }

    public function import(Request $request)
    {
        
        $request = request()->all();
        $g_prog_id = $request['program_id'];
        $g_fund_id = $request['fund_id'];
        $g_batch_desc = $request['batch_desc'];
        $g_batch_code = $request['batch_code'];
        $upload_batch_cde = $request['batch_code'];
        $g_user_id=session('uuid');
        $g_upload_date=date("Y-m-d H:i:s");
        $uuid = Str::uuid();


        if(request()->has('csv_f')){
            
            //$data = array_map('str_getcsv',file(request()->csv_f));
            $data = file(request()->csv_f);

            $rows_err = 0;
            $rows_cln = 0;
            
            unset($data[0]);
            unset($data[1]);
            unset($data[2]);
            unset($data[3]);
            unset($data[4]);
            unset($data[5]);

            $total_rows = count($data);

            DB::insert('insert into voucher_batch (batch_id, batch_code,batch_desc,total_rows,uploaded_by_id,uploaded_date) values (?, ?, ?, ?, ?, ?)', [$uuid, $g_batch_code,$g_batch_desc,$total_rows,$g_user_id,$g_upload_date]);

            // Chunking
            $chunks_csv = array_chunk($data,500);
            $path = resource_path('temp');

            foreach($chunks_csv as $key => $chunk){
                $name = "/tmp{$key}.csv";
                //return $path.$name;
                file_put_contents($path.$name,$chunk);

            }

            $files = glob("$path/*.csv*");
            
            foreach($files as $file){
                
                $data = array_map('str_getcsv',file($file));
                VUploadProcess::dispatch($data,$g_prog_id,$g_fund_id,$upload_batch_cde);
                unlink($file);
            
            }

        }


        return redirect()->route('batch-generation');
        
    }
    


    public function importchunk()
    {

        
        

    }

    public function DeleteBatch(Request $request)
    {

        $request = request()->all();
        $g_batch_code = $request['batch_code'];
        
        DB::table('voucher')
        ->where('batch_code', $g_batch_code) 
        ->whereIn('voucher_status', ['NOT YET CLAIMED'])
        ->update(array('voucher_status' => 'CANCELLED'));
        
        return redirect()->route('batch-generation');
    }

    public function GetSubCatAgainstMainCatEdit($id)
    {
        echo json_encode(DB::table('fund_source')->where('program_id',$id)->get());
    }
}
