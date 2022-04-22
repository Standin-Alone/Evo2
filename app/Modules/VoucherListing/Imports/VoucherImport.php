<?php

namespace App\Modules\VoucherListing\Imports;

use App\Modules\VoucherListing\Models\Voucher;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\withHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;



use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User; 
use Carbon\Carbon;

    
function generateRandomString($length = 10) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


class VoucherImport implements ToModel, WithStartRow
{

    

    private $rows = 0;
    private $rows_err = 0;
    private $rows_cln = 0;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */


    public function startRow():int
    {
        return 2;
    }

    public function model(array $row)
    {

        ++$this->rows;

        $request = request()->all();

        $g_prog_id = $request['program_id'];
        $g_fund_id = $request['fund_id'];
        $upload_batch_cde = $request['batch_code'];

        $ref_no = "DA".generateRandomString();

        $uuid = Str::uuid();

        $row_remarks = '';
        $row_v_status = 'NOT YET CLAIMED';

        $prv_match = DB::table('geo_map')->where('prov_name', $row[7])->first();
        
        if (empty($prv_match)) { $prv_cde = '0'; $row_remarks .= 'Invalid Province, '; } else { 
            
            $prv_cde = $prv_match->prov_code; 
            $reg_cde = $prv_match->reg_code;
            $reg_desc = $prv_match->reg_name;
        
        }

        $mun_match = DB::table('geo_map')->where('mun_name', $row[6])->first();
        
        if (empty($mun_match)) { $mun_cde = '0'; $row_remarks .= 'Invalid Municipality, '; } else { $mun_cde = $mun_match->mun_code; }

        $brgy_match = DB::table('geo_map')->where('bgy_name', $row[5])->first();
        
        if (empty($brgy_match)) { $brgy_cde = '0'; $row_remarks .= 'Invalid Barangay, '; } else { $brgy_cde = $brgy_match->bgy_code; }

        if (preg_match('/[^A-Za-z]/', str_replace(' ', '', $row[2]))) // '/[^a-z\d]/i' should also work.
        {
            $row_remarks .= 'First Name should only contain letters, ';
        }

        if (preg_match('/[^A-Za-z]/', str_replace(' ', '', $row[3]))) // '/[^a-z\d]/i' should also work.
        {
            $row_remarks .= 'Middle Name should only contain letters, ';
        }

        if (preg_match('/[^A-Za-z]/', str_replace(' ', '', $row[1]))) // '/[^a-z\d]/i' should also work.
        {
            $row_remarks .= 'Last Name should only contain letters, ';
        }

        if (preg_match('/[^A-Za-z]/', str_replace(' ', '', $row[4]))) // '/[^a-z\d]/i' should also work.
        {
            $row_remarks .= 'Ext Name should only contain letters, ';
        }

        if ($row[9]=="MALE" || $row[9]=="FEMALE") 
        {
            //if valid
        }else{
            $row_remarks .= 'Sex must be either MALE or FEMALE, ';
        }

        if ($row[9]=="MALE" || $row[9]=="FEMALE") 
        {
            //if valid
        }else{
            $row_remarks .= 'Sex must be either MALE or FEMALE, ';
        }

        $g_birth_date = date_format(date_create_from_format('m-d-Y', str_replace("/", "-", $row[8])), 'Y-m-d');

        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $g_birth_date )) {
            //return true;
        } else {
            $row_remarks .= 'Invalid Birthdate Format should be (mm/dd/yyyy), ';
        }

        $row_4p = $row[11];

        if ($row_4p=="YES") 
        {   
            $row_4ps = '1';
        }
        elseif ($row_4p=="NO") 
        {
            $row_4ps = '0';
        }
        else{
            $row_4ps = '';
            $row_remarks .= '4Ps should only be YES or NO, ';
        }

        $row_IP = $row[12];

        if ($row_IP=="YES") 
        {   
            $row_IP = '1';
        }
        elseif ($row_IP=="NO") 
        {
            $row_IP = '0';
        }
        else{
            $row_IP = '';
            $row_remarks .= '4Ps should only be YES or NO, ';
        }

        $row_PWD = $row[13];

        if ($row_PWD=="YES") 
        {   
            $row_PWD = '1';
        }
        elseif ($row_PWD=="NO") 
        {
            $row_PWD = '0';
        }
        else{
            $row_PWD = '';
            $row_remarks .= '4Ps should only be YES or NO, ';
        }

        $row_sclass = $row[15];

        if ($row_sclass=="HYBRID") 
        {   
            $row_sclass = '1';
            $row_amount = 1500*$row[14];
        }
        elseif ($row_sclass=="INBRED") 
        {
            $row_sclass = '2';
            $row_amount = 1000*$row[14];
        }
        else{
            $row_amount = '0';
        }

        $row_cstatus = $row[12];

        if ($row_cstatus == "SINGLE"){
            $row_cstatus = '1';
        } elseif ($row_cstatus == "MARRIED"){
            $row_cstatus = '1';
        } elseif ($row_cstatus == "WIDOWED"){
            $row_cstatus = '1';
        } elseif ($row_cstatus == "SEPARATED"){
            $row_cstatus = '1';
        } 

        if ($row_remarks!="") 
        {
            $row_v_status='WITH ERROR';
            ++$this->rows_err;
        }else{
            ++$this->rows_cln;
        }
         
        $g_u_id = session('uuid');
        $g_u_fllname = trim( session('first_name') . " " . session('middle_name') . " " . session('last_name') . " " . session('ext_name') );
        $g_geo_code =  $reg_cde . $prv_cde . $mun_cde . $brgy_cde;

        return new Voucher([

            'voucher_id' => $uuid,
            'rsbsa_no' => $row[0],
            'control_no' => $row[0],
            'reference_no' => $ref_no,
            'program_id' => $g_prog_id,
            'fund_id' => $g_fund_id,
            'type' => '',
            'first_name' => $row[2],
            'middle_name' => $row[3],
            'last_name' => $row[1],
            'ext_name' => $row[4],
            'sex' => $row[9],
            'birthday' => $g_birth_date,
            'birth_place' => '',
            'mother_maiden' => '',
            'contact_no' => $row[10],
            'civil_status' => '',
            'geo_code' => $g_geo_code,
            'reg' => $reg_cde,
            'reg_desc' => $reg_desc,
            'prv' => $prv_cde,
            'prv_desc' => $row[7],
            'mun' => $mun_cde,
            'mun_desc' => $row[6],
            'brgy' => $brgy_cde,
            'brgy_desc' => $row[5],
            'farm_area' => $row[14],
            'seed_class' => $row_sclass,
            'if_4ps' => $row_4ps,
            'if_ip' => $row_IP,
            'if_pwd' => $row_PWD,
            'sub_project' => '',
            'rrp_fertilizer_kind' => '',
            'amount' => $row_amount,
            'amount_val' => $row_amount,
            'fund_desc' => $row[17],
            'voucher_season' => $row[18],
            'voucher_status' => $row_v_status,
            'voucher_remarks' => $row_remarks,
            'batch_code' => $upload_batch_cde,
            'encoded_by_id' => $g_u_id,
            'encoded_by_fullname' => $g_u_fllname,

        ]);


    }
    
    public function getRowCount()
    {
        return [$this->rows,$this->rows_err,$this->rows_cln];
    }
    
}
