<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\EmployeeExport;
use Excel;
use App\Imports\VoucherImport;

class VoucherController extends Controller
{
    public function exportIntoExcel()
    {
    	return Excel::download(new EmployeeExport,'employeelist.xlsx');
    }

    public function exportIntoCSV()
    {
    	return Excel::download(new EmployeeExport,'employeelist.csv');
    }

    public function importForm()
    {
    	return view('import-form');
    }

    public function import(Request $request)
    {
    	Excel::import(new VoucherImport,$request->file);
    	return "Record are imported successfully";
    }
}
