<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\withHeadingRow;
use Illuminate\Support\Str;

class VoucherImport implements ToModel,withHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $uuid = Str::uuid();

        return new Voucher([
            
            'voucher_id' => $uuid,
            'rsbsa_no' => $row['rsbsa_no'],
            'control_no' => $row['control_no'],
            'reference_no' => $row['reference_no'],
            'program_id' => $row['program_id'],
            'fund_id' => $row['fund_id'],
            'type' => $row['type'],
            'first_name' => $row['first_name'],
            'middle_name' => $row['middle_name'],
            'last_name' => $row['last_name'],
            'ext_name' => $row['ext_name'],
            'sex' => $row['sex'],
            'birthday' => $row['birthday'],
            'birth_place' => $row['birth_place'],
            'mother_maiden' => $row['mother_maiden'],
            'contact_no' => $row['contact_no'],
            'civil_status' => $row['civil_status'],
            'reg' => $row['reg'],
            'prv' => $row['prv'],
            'mun' => $row['mun'],
            'brgy' => $row['brgy'],
            'farm_area' => $row['farm_area'],
            'seed_class' => $row['seed_class'],
            'sub_project' => $row['sub_project'],
            'rrp_fertilizer_kind' => $row['rrp_fertilizer_kind'],
            'amount' => $row['amount'],
            'fund_source' => $row['fund_source'],
        ]);
    }
}
