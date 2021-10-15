<?php

namespace App\Modules\ApprovedPayoutHistory\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ApprovedPayoutHistoryController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!empty(session('user_id'))) {
            return view("ApprovedPayoutHistory::index");
        }else{
            return redirect('/login');
        }
    }

    public function getApprovedPayoutHistoryList(Request $request){
        if ($request->ajax()) {
            $getData = DB::table('payout_gfi_details as pgd')
            ->select(DB::raw("pgd.payout_id,pgd.batch_id,pgd.transac_date,pgb.application_number,pgb.description,pgb.amount,pgb.issubmitted,
                (case when pgb.issubmitted=0 then pgb.amount else 0 end) totalcreated,p.description as program,
                (case when pgb.issubmitted=1 then pgb.amount else 0 end) totalpending"))
            ->leftJoin('payout_gif_batch as pgb', 'pgd.batch_id', '=', 'pgb.batch_id')
            ->leftJoin('programs as p', 'p.program_id', '=', 'pgb.program_id')
            ->where('p.program_id',session('Default_Program_Id'))  
            ->where('pgb.payout_endorse_approve',"1")
            ->groupBy("pgb.batch_id")
            ->get();

            return Datatables::of($getData)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $role_id = session('role_id');
                    if($role_id == 8){ // RFO Budget Supervisor
                        return '<a href="javascript:void(0)" data-selectedbatchid="'.$row->batch_id.'" class="btn btn-xs btn-outline-info btnViewApprovalHistoryDetails"><span class="fa fa-eye"></span><i class="fas fa-spinner fa-spin '.$row->batch_id.' pull-left m-r-10" style="display: none;"></i> View Details</a>
                        <a href="javascript:void(0)" data-selectedbatchid="'.$row->batch_id.'" class="btn btn-xs btn-outline-info btnViewApprovalHistoryApprove_B"><span class="fa fa-thumbs-up"></span><i class="fas fa-spinner fa-spin '.$row->batch_id.' pull-left m-r-10" style="display: none;"></i> Approve</a>';
                    }
                    else if($role_id == 10){ // RFO Disbursement Officer
                        return '<a href="javascript:void(0)" data-selectedbatchid="'.$row->batch_id.'" class="btn btn-xs btn-outline-info btnViewApprovalHistoryDetails"><span class="fa fa-eye"></span><i class="fas fa-spinner fa-spin '.$row->batch_id.' pull-left m-r-10" style="display: none;"></i> View Details</a>
                        <a href="javascript:void(0)" data-selectedbatchid="'.$row->batch_id.'" class="btn btn-xs btn-outline-info btnViewApprovalHistoryApprove_D"><span class="fa fa-thumbs-up"></span><i class="fas fa-spinner fa-spin '.$row->batch_id.' pull-left m-r-10" style="display: none;"></i> Approve</a>';
                    }else{
                        return '<a href="javascript:void(0)" data-selectedbatchid="'.$row->batch_id.'" class="btn btn-xs btn-outline-info btnViewApprovalHistoryDetails"><span class="fa fa-eye"></span><i class="fas fa-spinner fa-spin '.$row->batch_id.' pull-left m-r-10" style="display: none;"></i> View Details</a>';
                    }
                })
                // ADD COLUMN FOR GRAND TOTAL
                ->addColumn('grandtotalamount', function($row){
                    $getgrandtotal = DB::table('payout_gif_batch as pgb')
                    ->select(DB::raw("sum(pgb.amount) as grantotal"))
                    ->where('pgb.program_id',session('Default_Program_Id'))  
                    ->where('pgb.issubmitted',"1")
                    ->where('pgb.payout_endorse_approve',"1")
                    ->get();
                    $grandtotal=0;  
                    foreach ($getgrandtotal as $key => $value) {
                        $grandtotal += $value->grantotal;   
                    }                                     
                    return $grandtotal;
                })
                ->rawColumns(['action','grandtotalamount'])
                ->make(true);
        }
    }  
    
    public function getApprovedPayoutHistoryDetails(Request $request){        
        if ($request->ajax()) {
            $getData = DB::table('vw_claimed_voucher_items as vt')
                ->where('vt.batch_id',$request->batch_id)
                ->groupBy('vt.voucher_details_id')
                ->get();
            
                return Datatables::of($getData)
                ->addIndexColumn()
                ->addColumn('action', function($row){    
                    $viewData = DB::table('voucher_attachments')
                        ->select(DB::raw("attachment_id"))
                        ->where('voucher_details_id','=',$row->voucher_details_id)
                        ->exists();
                    if($viewData){
                        return '<a href="javascript:void(0)" data-selectvoucherid="'.$row->voucher_details_id.'" class="btn btn-xs btn-outline-info btnViewVoucherAttachments"><span class="fa fa-eye"></span><i class="fas fa-spinner fa-spin '.$row->voucher_details_id.' pull-left m-r-10" style="display: none;"></i> View Attachments</a>';
                        
                    }else{
                        return '<i class="text-danger">No attachement</i>';
                    }
                })              
                ->rawColumns(['action'])
                ->make(true);
        }   
    }

    public function getApprovedPayoutAttachmentsImg(Request $request){
        $getVoucherAttachmentsImg = DB::table('vw_claimed_voucher_att as va')
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

    public function approveBudgetSupervisorApproval(Request $request){
        return DB::table('payout_approval_log')        
        ->where('batch_id', $request->batch_id)
        ->update(['notedby_b' => 1,'notedby_b_date' => Carbon::now('GMT+8')]);
    }

    public function approveDisburstmentSupervisorApproval(Request $request){
        return DB::table('payout_approval_log')        
        ->where('batch_id', $request->batch_id)
        ->update(['notedby_d' => 1,'notedby_d_date' => Carbon::now('GMT+8')]);
    }
}
