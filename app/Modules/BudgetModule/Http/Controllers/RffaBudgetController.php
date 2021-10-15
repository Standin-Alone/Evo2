<?php

namespace App\Modules\BudgetModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\BudgetModule\Models\RffaBudgetModel;

class RffaBudgetController extends Controller
{
    public function __construct(Request $request)
    {
        $this->RffaBudgetModel = new RffaBudgetModel;

        $this->middleware('session.module');
    }

    public function rffa_fund_monitoring_and_disbursement_view(Request $request){
        $this->RffaBudgetModel->rffa_disbursement($request->get('programs_ids'));
        $programs = $this->RffaBudgetModel->get_program($request->get('programs_ids'));
    
        if($request->ajax()){
            return DataTables::of($this->RffaBudgetModel->rffa_disbursement($request->get('programs_ids')))
            ->addColumn('remaining_amount', function($row){
                return $row->total_amount - $row->disbursement_amount;
            })
            // ->addColumn('disbursement_amount', function($row){
            //     $return = '<a href="'.url('/budget/fund-monitoring-and-disbursement/view-rffa_disburse_breakdown/'.$row->fund_id.'/'.$row->title).'" id="btn_data" data-toggle="modal" data-target="#view_computation">'.$row->disbursement_amount.'
            //                </a>';

            //     return $return;
            // })
            // ->rawColumns(['disbursement_amount'])
            ->make(true);
        }            

        return view("BudgetModule::fund_monitoring_and_disbursement", ['programs' => $programs]);
    }

    public function rffa_disburse_breakdown(Request $request, $fund_id){
        if($request->ajax()){
            return DataTables::of($this->RffaBudgetModel->get_rffa_fund_source_breakdown($fund_id))->make(true);
        }  

        return view("BudgetModule::fund_source_breakdown");
    }
}
