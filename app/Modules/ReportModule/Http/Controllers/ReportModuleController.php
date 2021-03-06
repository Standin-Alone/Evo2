<?php

namespace App\Modules\ReportModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\ReportModule\Models\ReportModule;
use App\Modules\ReportModule\Models\RffaReportModel;

class ReportModuleController extends Controller
{
    public function __construct(Request $request)
    {
        $this->reportModel = new ReportModule;

        $this->rffa_report_Model = new RffaReportModel;

        $this->middleware('session.module');
    }

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("ReportModule::welcome");
    }

    public function report_main(){
        return view("ReportModule::index");
    }

    public function total_claim_vouchers(Request $request){
        $geo_map = $this->reportModel->get_region_and_province();
        
        $region = $this->reportModel->region();
        
        $province = $this->reportModel->province();

        $programs = $this->reportModel->get_program($request->get('programs_ids'));

        $program_title = $this->reportModel->show_program_title_on_zero_value($request->get('programs_ids'));

        $total_no_of_paid_voucher_transactions = $this->reportModel->total_number_of_paid_voucher_transactions();

        /**
         *  RRP 2 Dry Season (Grand Total)
         *  RRP 2 Wet Season (Grand Total)
         *  Cash and Food (Grand Total)
         *  Grand total of all claimed vouchers
         */
        $progs_total = $this->reportModel->total_amount_computation_active($request->get('programs_ids'));
        
        // $gt_dry = $this->reportModel->get_rrp_dry_season_grand_total($request->get('programs_ids'));

        // $gt_wet = $this->reportModel->get_rrp_wet_season_grand_total($request->get('programs_ids'));

        // $gt_csf= $this->reportModel->get_cash_and_food_grand_total($request->get('programs_ids'));

        $gt_grand_total = $this->reportModel->get_all_voucher_transaction_grand_total($request->get('programs_ids'));

        if($request->ajax()){
            return DataTables::of($this->reportModel->get_vt_active_payout($request->get('programs_ids')))
            ->addColumn('payout_status', function($row){
                $html = '<h4><span class="badge" style="background-color: rgba(57,218,138,.17); color: #39DA8A!important;" data-value="'.$row->payout.'">PAID</span></h4>';
                return $html;
            })
            // ->addColumn('grand_total', function($row){
            //     return $row->grand_total;
            // })
            ->rawColumns(['payout_status'])
            ->make(true);
        }

        return view("ReportModule::total_claimed_vouchers", [   'region'=>$region, 'province'=>$province, 'programs'=>$programs, 
                                                                // 'gt_dry' => $gt_dry,'gt_wet' => $gt_wet, 'gt_csf' => $gt_csf, 
                                                                'gt_grand_total'=>$gt_grand_total, 'progs_total' => $progs_total,
                                                                'total_no_of_paid_voucher_transactions' => $total_no_of_paid_voucher_transactions,
                                                                'program_title' => $program_title
                                                            ]);
    }

    public function claimed_not_yet_paid(Request $request){
        $geo_map = $this->reportModel->get_region_and_province();
    
        $region = $this->reportModel->region();
        
        $province = $this->reportModel->province();

        $programs = $this->reportModel->get_program($request->get('programs_ids'));

        /**
         *  RRP 2 Dry Season (Grand Total)
         *  RRP 2 Wet Season (Grand Total)
         *  Cash and Food (Grand Total)
         *  Grand total of all claimed vouchers
         */
        $count_no_of_not_paid = $this->reportModel->total_number_of_not_paid_voucher_transactions();

        $program_title = $this->reportModel->show_program_title_on_zero_value($request->get('programs_ids'));

        $progs_total = $this->reportModel->total_amount_computation_inactive($request->get('programs_ids'));

        // $gt_dry = $this->reportModel->get_inactive_rrp_dry_season_grand_total($request->get('programs_ids'));

        // $gt_wet = $this->reportModel->get_inactive_rrp_wet_season_grand_total($request->get('programs_ids'));

        // $gt_csf= $this->reportModel->get_inactive_cash_and_food_grand_total($request->get('programs_ids'));

        $gt_grand_total = $this->reportModel->get_all_inactive_voucher_transaction_grand_total($request->get('programs_ids'));

        if($request->ajax()){
            return DataTables::of($this->reportModel->get_vt_inactive_payout($request->get('programs_ids')))
            ->addColumn('payout_status', function($row){
                $html = '<h4><span class="badge" style="background-color: rgba(255,91,92,.17); color: #FF5B5C!important;" data-value="'.$row->payout.'">NOT YET PAID</span></h4>';
                return $html;
            })

            ->rawColumns(['payout_status'])
            ->make(true);
        }
        return view("ReportModule::claim_not_yet_paid", [   'region'=>$region, 'province'=>$province, 'programs'=>$programs,
                                                            // 'gt_dry' => $gt_dry, 'gt_wet' => $gt_wet, 'gt_csf' => $gt_csf, 
                                                            'gt_grand_total'=>$gt_grand_total, 'program_title' => $program_title, 
                                                            'progs_total' => $progs_total, 'count_no_of_not_paid' => $count_no_of_not_paid
                                                        ]);
    }

    // Get Region by Selected Province
    public function get_region($prov_name){
        $regions = $this->reportModel->get_filter_region($prov_name);

        return response()->json($regions);
    }
    
    // Get Province by Selected Region
    public function get_province($reg_name){
        $provinces = $this->reportModel->get_filter_province($reg_name);

        return response()->json($provinces);
    }

    public function get_municipality($reg_name, $prov_name){
        $municipalities = $this->reportModel->get_filter_municipality($reg_name, $prov_name);

        return response()->json($municipalities);
    }

    // Get all the Province
    public function get_region_without_province(){
        $regions = $this->reportModel->region();

        return response()->json($regions);
    }

    // Get all the Region
    public function get_province_without_region(){
        $provinces = $this->reportModel->province();

        return response()->json($provinces);
    }

    // Get all the Municipality
    public function get_municipality_without_province(){
        $municipalities = $this->reportModel->municipality();

        return response()->json($municipalities);
    }

    public function ready_vouchers(Request $request){
        $geo_map = $this->reportModel->get_region_and_province();
        
        $region = $this->reportModel->region();
        
        $province = $this->reportModel->province();

        $total_ready_voucher_grandtotal = $this->reportModel->ready_voucher_grand_total();

        $dashboard_cards = $this->reportModel->ready_voucher_dashboard_computation();

        if($request->ajax()){
            return DataTables::of($this->reportModel->total_ready_vouchers())->make(true);
        }
        return view("ReportModule::total_ready_vouchers", ['region'=>$region, 'province'=>$province, 
                                                            'total_ready_voucher_grandtotal' => $total_ready_voucher_grandtotal,
                                                            'dashboard_cards' => $dashboard_cards
                                                        ]);
    }

    public function report_summary(Request $request){
        $supp = $this->reportModel->get_supplier();

        $supplier = $supp['supplier'];

        /**
         *  Grand total of Summary Claims by Supplier
         *  Grand total of Voucher Claimed
         *  Grand total of Claimed Not Yet Paid
         */
        // $progs_total = $this->reportModel->total_amount_computation($request->get('programs_ids'));
        $gt_voucher_claimed = $this->reportModel->get_all_voucher_transaction_grand_total($request->get('programs_ids'));
        $gt_not_paid =  $this->reportModel->get_all_inactive_voucher_transaction_grand_total($request->get('programs_ids'));
        $gt_grand_total = $this->reportModel->get_summary_claims_grand_total($request->get('programs_ids'));

        if($request->ajax()){
            return DataTables::of($this->reportModel->get_summary_claims($request->get('programs_ids')))->make(true);
        }

        return view("ReportModule::summary_claims", ['supplier' => $supplier, 'gt_grand_total'=>$gt_grand_total,
                                                     'gt_voucher_claimed' => $gt_voucher_claimed, 'gt_not_paid' => $gt_not_paid, 
                                                    ]);
    }
}
