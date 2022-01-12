<?php

namespace App\Modules\PayoutMonitoring\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\PayoutMonitoring\Models\PayoutMonitoring;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class PayoutMonitoringController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!empty(session('uuid'))) {
            return view("PayoutMonitoring::index");
        }else{
            return redirect('/login');
        }
    }

    public function getPayoutMonitoringContent(Request $request){
        if ($request->ajax()) {
            $getData = DB::table('payout_gif_batch as pgb')
                ->select(DB::raw("pgb.batch_id,pgb.program_id,p.description as program,s.supplier_name,pgb.transac_date,pgb.application_number,pgb.description,pgb.amount,
                (case when payout_endorse_approve='1' then 'APPORVED' else 'PENDING' end) as payout_status"))
                ->leftJoin('programs as p', 'p.program_id', '=', 'pgb.program_id')
                ->leftJoin('supplier as s', 's.supplier_id', '=', 'pgb.supplier_id')
                ->where('pgb.issubmitted',"1")
                ->get();
            return Datatables::of($getData)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '<a href="javascript:void(0)" data-selectedbatchid="'.$row->batch_id.'" data-toggle="modal" data-target="#ViewPayoutMonitoringDetailsModal" 
                    class="btn btn-xs btn-outline-info btnPayoutMonitoringDetails"><span class="fa fa-eye"></span> View Details</a>';
                })             
                ->addColumn('totalpendingamt', function($prm_pending){
                    $totalpending = DB::table('payout_gfi_details as pgd')
                    ->select(DB::raw("(pgb.amount) as totalamt_pending"))
                    ->leftJoin('payout_gif_batch as pgb', 'pgd.batch_id', '=', 'pgb.batch_id')
                    ->where('pgb.supplier_id',session('supplier_id'))
                    ->where('pgb.program_id',session('Default_Program_Id'))
                    ->where('pgb.issubmitted',"1")
                    ->where('pgb.payout_endorse_approve',"0")
                    ->groupBy('pgd.batch_id')
                    ->get();
                    $totalpendingval=0;  
                    foreach ($totalpending as $key => $pending_val) {
                        $totalpendingval += $pending_val->totalamt_pending;   
                    }                                     
                    return $totalpendingval;
                })
                ->addColumn('totalapprovedamt', function($prm_approved){
                    $totalapproved = DB::table('payout_gfi_details as pgd')
                    ->select(DB::raw("(pgb.amount) as totalamt_approved"))
                    ->leftJoin('payout_gif_batch as pgb', 'pgd.batch_id', '=', 'pgb.batch_id')
                    ->where('pgb.supplier_id',session('supplier_id'))
                    ->where('pgb.program_id',session('Default_Program_Id'))
                    ->where('pgb.issubmitted',"1")
                    ->where('pgb.payout_endorse_approve',"1")
                    ->groupBy('pgd.batch_id')
                    ->get();
                    $totalapprovedval=0;  
                    foreach ($totalapproved as $key => $approved_val) {
                        $totalapprovedval += $approved_val->totalamt_approved;   
                    }                                     
                    return $totalapprovedval;
                })
                ->rawColumns(['action','totalpendingamt','totalapprovedamt'])
                ->make(true);
        }
    }

    public function getPayoutMonitoringDetails(Request $request){  
        if ($request->ajax()) {
            $getData = DB::table('vw_claimed_voucher_items as vt')
                ->where('vt.batch_id',$request->batch_id)
                ->get();
            
                return Datatables::of($getData)
                ->addIndexColumn()
                ->addColumn('action', function($row){    
                    $viewData = DB::table('voucher_attachments')
                            ->select(DB::raw("attachment_id"))
                            ->where('voucher_details_id','=',$row->voucher_details_id)
                            ->exists();
                        if($viewData){
                            return '<a href="javascript:void(0)" data-selectvoucherid="'.$row->voucher_details_id.'" class="btn btn-xs btn-outline-info btnViewPayoutMonitoringattach"><span class="fa fa-eye"></span> View Attachments</a>';
                            
                        }else{
                            return '<i class="text-danger">No attachement</i>';
                        }          
                   
                    
                })               
                ->rawColumns(['action'])
                ->make(true);
        }   
    }

    public function getPayoutMonitoringAttachImg(Request $request){        
        $getVoucherAttachmentsImg = DB::table('voucher_attachments as va')
            ->select(DB::raw("va.document,va.file_name,v.rsbsa_no,year(va.created_at) as transac_year"))
            ->leftjoin('voucher as v','v.voucher_id','=','va.voucher_details_id')
            ->where('va.voucher_details_id','=',$request->voucher_id)
            ->get();
            $output = [];        
            if(!empty($getVoucherAttachmentsImg)){
                foreach ($getVoucherAttachmentsImg as $key => $imgview) {
                    try {
                        if($key == 0){                
                            array_push($output,'<div class="carousel-item active">
                            <img id="VoucherAttachmentsImage'.$key.'" src="data:image/jpeg;base64,'.base64_encode(file_get_contents(('uploads'.'/'.'transactions'.'/'.'attachments'.'/'.session('Default_Program_shortname').'/'.$imgview->transac_year.'/' . $imgview->rsbsa_no.'/'.$imgview->file_name.'.jpg'))).'" alt="First slide" style="width:100%; height:auto;">
                                <div class="carousel-caption d-none d-md-block">
                                    <p style="color:gray;">'.$imgview->document.'</p>
                                    <h5>'.$imgview->file_name.'</h5>                                
                                </div>
                            </div>');
                        }else{
                            array_push($output,'<div class="carousel-item ">
                            <img id="VoucherAttachmentsImage'.$key.'" src="data:image/jpeg;base64,'.base64_encode(file_get_contents(('uploads'.'/'.'transactions'.'/'.'attachments'.'/'.session('Default_Program_shortname').'/'.$imgview->transac_year.'/' . $imgview->rsbsa_no.'/'.$imgview->file_name.'.jpg'))).'" alt="First slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <p style="color:gray;">'.$imgview->document.'</p>
                                    <h5>'.$imgview->file_name.'</h5>                                
                                </div>
                            </div>');
                        }
                    } catch (\Throwable $th) {
                        $output = '
                        <div class="carousel-item active">
                            <img id="VoucherAttachmentsImage" src="data:image/jpg;base64,'.base64_encode(file_get_contents(asset('assets/img/gallery/noimage.jpg'))).'" alt="First slide">
                        </div>';
                    }
                } 
            }else{
                $output = '
                    <div class="carousel-item active">
                        <img id="VoucherAttachmentsImage" src="data:image/jpg;base64,'.base64_encode(file_get_contents(asset('assets/img/gallery/noimage.jpg'))).'" alt="First slide">
                    </div>';
            }
            return $output;
    }
}
