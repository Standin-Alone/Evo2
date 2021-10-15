<?php

namespace App\Modules\ReportModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\ReportModule\Models\ReportModule;

class ReportModuleController extends Controller
{
    public function __construct(Request $request)
    {
        $this->reportModel = new ReportModule;

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

        // Datatable
        if($request->ajax()){
            return DataTables::of($this->reportModel->get_vt_active_payout($request->get('programs_ids')))
            ->addColumn('payout_status', function($row){
                $html = '<h4><span class="badge" style="background-color: green;" data-value="'.$row->payout.'">PAID</span></h4>';
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
                                                                'gt_grand_total'=>$gt_grand_total, 'progs_total' => $progs_total
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
        $progs_total = $this->reportModel->total_amount_computation_inactive($request->get('programs_ids'));

        // $gt_dry = $this->reportModel->get_inactive_rrp_dry_season_grand_total($request->get('programs_ids'));

        // $gt_wet = $this->reportModel->get_inactive_rrp_wet_season_grand_total($request->get('programs_ids'));

        // $gt_csf= $this->reportModel->get_inactive_cash_and_food_grand_total($request->get('programs_ids'));

        $gt_grand_total = $this->reportModel->get_all_inactive_voucher_transaction_grand_total($request->get('programs_ids'));

        // Datatable
        if($request->ajax()){
            return DataTables::of($this->reportModel->get_vt_inactive_payout($request->get('programs_ids')))
            ->addColumn('payout_status', function($row){
                $html = '<h4><span class="badge badge-danger" data-value="'.$row->payout.'">NOT YET PAID</span></h4>';
                return $html;
            })

            ->rawColumns(['payout_status'])
            ->make(true);
        }
        return view("ReportModule::claim_not_yet_paid", [   'region'=>$region, 'province'=>$province, 'programs'=>$programs,
                                                            // 'gt_dry' => $gt_dry, 'gt_wet' => $gt_wet, 'gt_csf' => $gt_csf, 
                                                            'gt_grand_total'=>$gt_grand_total, 'progs_total' => $progs_total
                                                        ]);
    }

    public function get_region($prov_name){
        $regions = $this->reportModel->get_filter_region($prov_name);

        return response()->json($regions);
    }
    
    public function get_province($reg_name){
        $provinces = $this->reportModel->get_filter_province($reg_name);

        return response()->json($provinces);
    }

    public function get_region_without_province(){
        $regions = $this->reportModel->region();

        return response()->json($regions);
    }

    public function get_province_without_region(){
        $provinces = $this->reportModel->province();

        return response()->json($provinces);
    }

    public function ready_vouchers(Request $request){
        $geo_map = $this->reportModel->get_region_and_province();
        
        $region = $this->reportModel->region();
        
        $province = $this->reportModel->province();

        // Datatable
        if($request->ajax()){
            return DataTables::of($this->reportModel->total_ready_vouchers())->make(true);
        }
        return view("ReportModule::total_ready_vouchers", ['region'=>$region, 'province'=>$province]);
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
                                                    //  'progs_total' => $progs_total
                                                    ]);
    }
}
