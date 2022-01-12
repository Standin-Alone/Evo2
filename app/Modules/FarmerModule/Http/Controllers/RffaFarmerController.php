<?php

namespace App\Modules\FarmerModule\Http\Controllers;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Modules\FarmerModule\Models\FarmerModule;

class RffaFarmerController extends Controller
{
    public function __construct(Request $request)
    {
        $this->farmerModel = new FarmerModule;
    }

    public function view_rffa_farmer_details_page(Request $request, $program_id, $dbp_batch_id, $rsbsa_no){
        $farmer = $this->farmerModel->get_rffa_fullname_and_rsbsa($rsbsa_no);

        $farmer_dbp_batch_id = $this->farmerModel->get_dbp_batch($dbp_batch_id, $rsbsa_no);
        // dd($farmer_dbp_batch_id);

        if($request->ajax()){
            return DataTables::of($farmer_dbp_batch_id)
            ->addColumn('fullname_column', function($row){
                return $row->last_name.' '.$row->first_name.' '.$row->middle_name.' '.$row->ext_name;
            })
            ->addColumn('processing_fee', function($row){
                $processing_fee = ($row->amount == 5070 ? '70.00' : '0.00');
                return $processing_fee;
            })
            ->addColumn('total_amount', function($row){
                return '5000.00';
            })
            ->addColumn('status', function($row){
                return '<h4><span class="badge" style="background-color: rgba(57,218,138,.17); color: #39DA8A!important;">CLAIMED</span></h4>';
            })
            ->rawColumns(['processing_fee', 'total_amount', 'status'])
            ->make(true);
        } 

        return view("FarmerModule::farmer_details", ['farmer' => $farmer], ['program_id' => $program_id]);
    }
}
