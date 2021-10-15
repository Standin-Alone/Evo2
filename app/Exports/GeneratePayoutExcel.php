<?php

namespace App\Exports;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Illuminate\Contracts\Encryption\DecryptException;

class GeneratePayoutExcel implements FromCollection, ShouldAutoSize, WithColumnFormatting
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $row_value = [];
        $totalamt = 0;
        $dbp_batch_id = Uuid::uuid4();
        $getData = DB::table('payout_export')
                    ->where('file_name',session('program_File_Name'))
                    ->get();
        $total_records = 0;

        $getDBP_file_Series = "001"; 
        $get_fileseries = DB::table('dbp_batch')->select(DB::raw("MAX(RIGHT(concat('000',right(file_series,3)+1),3)) as file_series"))
            ->where('prv_code',session('user_prv_code'))
            ->groupBy('file_series')
            ->orderBy('file_series', 'DESC')
            ->take(1)
            ->get();
        foreach ($get_fileseries as $key => $series) {
            $getDBP_file_Series = $series->file_series;
        } 
            foreach ($getData as $key => $value) {                

                $row_value[] = [$value->settlement_currency,$value->funding_currency,$value->today_date,$value->service_code,
                $value->application_number,$value->amount,$value->account_number,$value->bank_name,$value->remarks,$value->outlet_name,
                $value->bene_name1,$value->bene_name2,$value->bene_name3,$value->barangay,$value->city_municipality,$value->province,
                $value->beneficiary_telnum,$value->contact_num,$value->message,$value->remitter_name_1,$value->remitter_name_2,$value->remitter_name_3,
                $value->remitter_id,$value->beneficiary_id,$value->remitter_address_1,$value->remitter_address_2,$value->remitter_address_3,
                $value->institution_code,$value->institution_id_number,$value->institution_detail1,$value->institution_detail2,$value->institution_detail3];
                $totalamt += $value->amount;
                $file_name = $value->file_name;  
                
                $bank_account_no = $value->account_number;    
                $bank_account_name = $value->bank_name; 
                
                $nonceSize = openssl_cipher_iv_length('aes-256-ctr');
                $nonce = openssl_random_pseudo_bytes($nonceSize);
                $ciphertext = openssl_encrypt(
                $bank_account_no, 
                'aes-256-ctr', 
                session('private_secret_key'),
                OPENSSL_RAW_DATA,
                $nonce
                ); 
                $bank_account_no = base64_encode($nonce.$ciphertext);

                $nonceSize1 = openssl_cipher_iv_length('aes-256-ctr');
                $nonce1 = openssl_random_pseudo_bytes($nonceSize1);
                $ciphertext1 = openssl_encrypt(
                $bank_account_name, 
                'aes-256-ctr', 
                session('private_secret_key'),
                OPENSSL_RAW_DATA,
                $nonce1
                ); 
                $bank_account_name = base64_encode($nonce1.$ciphertext1);

                // UPDATE BANK DETAILS
                DB::table('payout_export')
                ->where('file_name', $value->file_name)
                ->update(['account_number' => $bank_account_no,'bank_name' => $bank_account_name]); 

                // UPDATE BATCH FOR DBP ID  
                DB::table('payout_gif_batch')
                ->where('application_number', $value->application_number)
                ->update(['dbp_batch_id' => $dbp_batch_id]);  
                $total_records += 1;
            }

            // ADD RECORD TO DBP_BATCH TABLE
            DB::table('dbp_batch')->insert([
                'dbp_batch_id'=>$dbp_batch_id,
                'date'=>Carbon::now('GMT+8'),            
                'name'=>$file_name,  
                'total_amount'=>$totalamt,
                'status'=>1,
                'created_at'=>Carbon::now('GMT+8'),
                'created_by_id'=>session('user_id'),
                'created_by_fullname'=>session('user_fullname'), 
                'prv_code'=>session('user_prv_code'),      
                'file_series'=>$getDBP_file_Series,  
                'program_id'=>session('Default_Program_Id'), 
                'total_records'=>$total_records,           
            ]);

        return new Collection([
            
            // COLUMN NAMES
            ['SETTLEMENT_CURRENCY','FUNDING_CURRENCY','TODAY_DATE','SERVICE_CODE','APPLICATION_NUMBER',
            'AMOUNT','ACCOUNT_NUMBER','BANK_NAME','REMARKS','OUTLET_NAME','BENE_NAME1','BENE_NAME2',
            'BENE_NAME3','BARANGAY','CITY_MUNICIPALITY','PROVINCE','BENEFICIARY_TELNUM','CONTACT_NUM',
            'MESSAGE','REMITTER_NAME_1','REMITTER_NAME_2','REMITTER_NAME_3','REMITTER_ID','BENEFICIARY_ID',
            'REMITTER_ADDRESS_1','REMITTER_ADDRESS_2','REMITTER_ADDRESS_3','INSTITUTION_CODE',
            'INSTITUTION_ID_NUMBER','INSTITUTION_DETAIL1','INSTITUTION_DETAIL2','INSTITUTION_DETAIL3'], 
            
            // ROW DETAILS
            $row_value
        ]);       

    }

    // COLUMN C MODIFY DATA TYPE AS DATE
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY
        ];
    }

}


