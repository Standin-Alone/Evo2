<?php

namespace App\Modules\FarmerModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\QueryRepetitiveUseModel;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\FarmerModule\Models\FarmerModule;

class FarmerModuleController extends Controller
{
    public function __construct(Request $request)
    {
        $this->farmerModel = new FarmerModule;

        $this->RepetitiveQuery = new QueryRepetitiveUseModel;

        $this->middleware('session.module');
    }

    // If the program is RFFA = True
    // Else the progam are Cash and Food, RRP2 Wet or RPP2 Dry 
    public function index(){
        $programs = $this->RepetitiveQuery->get_program_on_program_permission(request()->get('programs_ids'));
        $program_ids = request()->get('programs_ids');

        // $rffa = $this->farmerModel->get_kyc_profile($program_ids);
        // $rrp2_dry = $this->farmerModel->get_rrp2_dry_voucher();
        // $csf = $this->farmerModel->get_csf_voucher();
        // $rrp2_wet = $this->farmerModel->get_rrp2_wet_voucher();

        if(request()->ajax()){
            return DataTables::of($this->farmerModel->get_voucher($program_ids))
            ->addColumn('fullname_column', function($row){
                return $row->last_name.' '.$row->first_name.' '.$row->middle_name.' '.$row->ext_name;
            })
            ->addColumn('action', function($row){
                $html = '<a href="'.url(route('farmer.view.details.page', ['program_id'=>$row->program_id, $row->reference_no])).'" id="btn_data" type="button" class="btn btn-success">
                            <i class="fa fa-eye"></i> View
                        </a>';
                return $html;
            })
            ->make(true);
        }  
        return view("FarmerModule::index", ['program_ids'=> $program_ids, 'programs' => $programs]);
    }

    public function rffa_farmer_list_view(){
        $programs = $this->RepetitiveQuery->get_program_on_program_permission(request()->get('programs_ids'));
        $program_ids = request()->get('programs_ids');

        if(request()->ajax()){
            return DataTables::of($this->farmerModel->get_kyc_profile($program_ids))
            ->addColumn('fullname_column', function($row){
                return $row->last_name.' '.$row->first_name.' '.$row->middle_name.' '.$row->ext_name;
            })
            ->addColumn('action', function($row){
                $html = '<a href="'.url(route('farmer.view.rffa_details.page', ['program_id'=>$row->program_id, 'dbp_batch_id' => $row->dbp_batch_id, 'rsbsa_no' => $row->rsbsa_no])).'" id="btn_data" type="button" class="btn btn-success">
                            <i class="fa fa-eye"></i> View
                        </a>';       

                return $html;
            })
            ->make(true);
        }  
        return view("FarmerModule::index", ['program_ids'=> $program_ids, 'programs' => $programs]);
    }

    public function view_farmer_details_page($program_id, $ref_no){
        $farmer = $this->farmerModel->get_fullname_and_rsbsa($ref_no);

        $voucher_transaction = $this->farmerModel->get_voucher_transaction($ref_no);

        if(request()->ajax()){
            return DataTables::of($voucher_transaction)
            ->addColumn('fullname_column', function($row){
                return $row->last_name.' '.$row->first_name.' '.$row->middle_name.' '.$row->ext_name;
            })
            ->make(true);
        } 
 
        return view("FarmerModule::farmer_details", ['voucher_transaction' => $voucher_transaction, 'farmer' => $farmer, 'program_id' => $program_id]);
    }
}
