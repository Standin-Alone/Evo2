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

class GenerateDisbursementExcel implements FromCollection, ShouldAutoSize, WithColumnFormatting
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
        $getData = DB::table('excel_export as ee')
                    ->select(DB::raw("ee.funding_currency,ee.today_date,ee.imc,
                    ee.ewallet_number,
                    ee.amount,ee.rsbsa_no,ee.fintech,ee.first_name,ee.middle_name,ee.last_name,ee.street_purok,ee.city_municipality,
                    ee.province,ee.beneficiary_telnum,ee.contact_num,ee.message,ee.remitter_name_1,ee.remitter_name_2,
                    ee.remitter_name_3,ee.remitter_pro_id,ee.beneficiary_id,ee.remitter_address_1,ee.remitter_city,ee.remitter_province"))
                    ->where('ee.file_name',session('Downloaded_filename'))
                    ->get();
        
            foreach ($getData as $key => $row) {                
                $row_value[] = [$row->funding_currency,$row->today_date,$row->imc,$row->ewallet_number,$row->amount,$row->rsbsa_no,
                $row->fintech,$row->first_name,$row->middle_name,$row->last_name,$row->street_purok,$row->city_municipality,
                $row->province,$row->beneficiary_telnum,$row->contact_num,$row->message,$row->remitter_name_1,$row->remitter_name_2,
                $row->remitter_name_3,$row->remitter_pro_id,$row->beneficiary_id,$row->remitter_address_1,$row->remitter_city,$row->remitter_province];
            } 

        return new Collection([
            
            // COLUMN NAMES
            ['FUNDING CURRENCY','REMITTANCE DATE','SERVICE CODE','E-WALLET NUMBER','REMITTANCE AMOUNT','RSBSA NUMBER','OUTLET NAME',
            'BENEFICIARY NAME 1','BENEFICIARY NAME 2','BENEFICIARY NAME 3','BENEFICIARY ADDRESS 1','BENEFICIARY ADDRESS 2','BENEFICIARY ADDRESS 3',
            'BENEFICIARY TELEPHONE NO.','BENEFICIARY MOBILE NUMBER','MESSAGE','REMITTER NAME 1','REMITTER NAME 2','REMITTER NAME 3','REMITTER ID',
            'BENEFICIARY ID','REMITTER ADDRESS 1','REMITTER ADDRESS 2','REMITTER ADDRESS 3'],
            
            // ROW DETAILS
            $row_value
        ]);  

    }

    // COLUMN C MODIFY DATA TYPE AS DATE
    public function columnFormats(): array
    {
    
        return [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY
        ];
    }

}


