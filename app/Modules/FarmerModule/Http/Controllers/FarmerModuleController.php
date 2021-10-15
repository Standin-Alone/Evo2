<?php

namespace App\Modules\FarmerModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\FarmerModule\Models\FarmerModule;

class FarmerModuleController extends Controller
{
    public function __construct(Request $request)
    {
        $this->farmerModel = new FarmerModule;
    }

    public function index(){
        if(request()->ajax()){
            return DataTables::of($this->farmerModel->get_voucher())
            ->addColumn('fullname_column', function($row){
                return $row->last_name.' '.$row->first_name.' '.$row->middle_name.' '.$row->ext_name;
            })
            ->addColumn('action', function($row){
                $html = '<a href="'.url(route('farmer.view.details.page', [$row->reference_no])).'" id="btn_data" type="button" class="btn btn-success">
                            <i class="fa fa-eye"></i> View
                        </a>';

                return $html;
            })
            ->make(true);
        }  
        return view("FarmerModule::index");
    }

    public function view_farmer_details_page(Request $request, $ref_no){
        $farmer = $this->farmerModel->get_fullname_and_rsbsa($ref_no);

        $voucher_transaction = $this->farmerModel->get_voucher_transaction($ref_no);

        if($request->ajax()){
            return DataTables::of($voucher_transaction)
            ->addColumn('fullname_column', function($row){
                return $row->last_name.' '.$row->first_name.' '.$row->middle_name.' '.$row->ext_name;
            })
            ->make(true);
        } 

        return view("FarmerModule::farmer_details", ['voucher_transaction' => $voucher_transaction], ['farmer' => $farmer]);
    }
}
