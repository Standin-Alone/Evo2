<?php

namespace App\Modules\BudgetModule\Http\Controllers;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\BudgetModule\Models\BudgetModule;
use App\Modules\BudgetModule\Models\RffaBudgetModel;

class BudgetModuleController extends Controller
{
    public function __construct(Request $request)
    {
        $this->BudgetModel = new BudgetModule;
        $this->RffaBudgetModel = new RffaBudgetModel;

        $this->middleware('session.module');
    }

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view("BudgetModule::index");
    }

    public function fund_source_encoding_view(Request $request){
        $programs = $this->BudgetModel->get_program($request->get('programs_ids'));

        $regions = $this->BudgetModel->get_reg();

        $action = $request->get('action');
        
        return view("BudgetModule::fund_source_encoding", compact("programs", "regions", "action"));
    }

    public function get_province($reg_code){
        $provinces = $this->BudgetModel->get_prov($reg_code);

        return response()->json($provinces);
    }

    public function fund_encoding_ors(Request $request){
        $program = $request->select_program;
        $uacs = $request->uacs;
        $gfi = $request->select_gfi;
        $farmers = $request->no_of_farmers;
        $amount = floatval(preg_replace('/[^\d.]/', '', $request->amount));
        $region = $request->select_region;
        $province = $request->select_province;
        $particulars = $request->particulars;

        $this->BudgetModel->insert_new_fund($program, $uacs, $gfi, $farmers, $amount, $region, $province, $particulars);

        $success_response = ["success" => true, "message" => "ORS form have been submit successfully!"];
        return response()->json($success_response, 200);
    }

    public function fund_monitoring_and_disbursement_view(Request $request){
        $programs = $this->BudgetModel->get_program($request->get('programs_ids'));
        // $rffa = $this->RffaBudgetModel->rffa_disbursement($request->get('programs_ids'));
        if($request->ajax()){
            return DataTables::of($this->BudgetModel->disbursement_v2($request->get('programs_ids')))
            ->addColumn('disbursement_amount', function($row){
                if($row->program_id == "212a7b35-b251-48a6-9928-3f689321d8b1" || $row->program_id == "42383225-3a4e-4e18-8cda-deed9a62775f")
                $return = '<a href="'.url('/budget/fund-monitoring-and-disbursement/view-fund-source-breakdown/'.$row->fund_id.'/'.$row->prog_desc).'" data-id="'.$row->fund_id.'" data-prog_desc="'.$row->prog_desc.'" id="voucher_fund_btn_data" data-toggle="modal" data-target="#view_voucher_breakdown_computation">&#8369;'.number_format($row->disbursement_amount, 2, '.', ',').'
                            </a>';
                else
                $return = '<a href="'.url('/budget/fund-monitoring-and-disbursement/rffa/'.$row->fund_id).'" data-id="'.$row->fund_id.'" data-title="'.$row->title.'" id="rffa_fund_btn_data" data-toggle="modal" data-target="#view_computation">&#8369;'.number_format($row->disbursement_amount, 2, '.', ',').'
                            </a>';

                return $return;
            })
            // ->addColumn('progress_bar', function($row){
            //     $return = '<div>'.(($row->disbursement_amount/$row->total_amount)*100).'%</div> <br/> <div class="progress rounded-corner progress-striped active">
            //                     <div class="progress-bar bg-purple" style="width:'.(($row->disbursement_amount/$row->total_amount)*100).'%"></div>
            //                 </div>';

            //     return $return;
            // })
            ->rawColumns(['disbursement_amount'])
            ->make(true);
        }            

        return view("BudgetModule::fund_monitoring_and_disbursement", ['programs' => $programs]);
    }

    public function get_fund_source_breakdown(Request $request, $fund_id, $reg_program){
        if($request->ajax()){
            return DataTables::of($this->BudgetModel->breakdown($fund_id, $reg_program))
            ->addColumn('fullname_column', function($row){
                return $row->last_name.', '.$row->first_name.' '.$row->middle_name;
            })
            ->make(true);
        }  

        return view("BudgetModule::fund_source_breakdown");
    }

    public function fund_overview(Request $request){
        $regions = $this->BudgetModel->get_reg();
        
        if($request->ajax()){
            return DataTables::of($this->BudgetModel->overview($request->get('programs_ids')))
            ->addColumn('action', function($row){
                $return = '<a href="#" id="btn_overview_edit" type="button" class="btn btn-warning" 
                data-id="'.$row->fund_id.'" 
                data-uacs="'.$row->uacs.'" 
                data-gfi="'.$row->gfi.'" 
                data-no_of_farmers="'.$row->target_of_benefeciaries.'" 
                data-region="'.$row->reg.'" 
                data-region_name="'.$row->region.'"
                data-amount="'.$row->amount.'" 
                data-particulars="'.$row->particulars.'" 
                data-toggle="modal" data-target="#edit_overview_modal">
                            <i class="fa fa-edit"></i> Edit
                           </a>';
                
                return $return;
            })
            ->rawColumns(['action'])
            ->make(true);
        }  

        return view("BudgetModule::fund_source_overview", ['regions' => $regions]);
    }

    public function update_amount_overview(Request $request){
        $fund_id = $request->fund_id;

        $uacs = $request->uacs;
        
        $gfi = $request->select_gfi;

        $farmers = $request->no_of_farmers;
        
        $amount = floatval(preg_replace('/[^-\d.]/', '', $request->amount));
        
        $region = $request->select_region_hidden;
        
        // return var_dump($region);
        // if($request->select_region == null){
        //     $region = $request->select_region_hidden;
        // }else{
        //     // dd('pasok');
        //     $region = $request->select_region;
        // }

        $province = $request->select_province;
        
        $particulars = $request->particulars;

        $this->BudgetModel->update_overview_amount($fund_id, $uacs, $gfi, $farmers, $amount, $region, $particulars);

        $success_response = ["success" => true, "message" => "The data have been update successfully!"];
        return response()->json($success_response, 200);
    }
}
