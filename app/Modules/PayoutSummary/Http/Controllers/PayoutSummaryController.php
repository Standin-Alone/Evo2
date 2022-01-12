<?php

namespace App\Modules\PayoutSummary\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PayoutSummaryController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if (!empty(session('uuid'))) {
            return view("PayoutSummary::index");
        }else{
            return redirect('/login');
        }
    }

    public function getPayoutSummaryList(Request $request){
        if ($request->ajax()) {
            $getData = DB::table('vw_payout_summary as ps')
            ->where('ps.supplier_id',session('supplier_id'))
            ->where('ps.program_id',session('Default_Program_Id'))
            ->where('ps.payout_endorse_approve',"1")
            ->get();
            return Datatables::of($getData)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '<a href="javascript:void(0)" data-selectedbatchid="'.$row->batch_id.'" data-toggle="modal" data-target="#ViewPayoutSummaryDetailsModal" class="btn btn-xs btn-outline-info PayoutSummaryDetails_btn_view"><span class="fa fa-eye"></span> View Details</a>';
                })
                // ADD COLUMN FOR GRAND TOTAL
                ->addColumn('grandtotalamount', function($value){
                    $getgrandtotal = DB::table('payout_gif_batch as pgb')
                    ->select(DB::raw("sum(pgb.amount) as grantotal"))
                    ->where('pgb.supplier_id',session('supplier_id'))
                    ->where('pgb.program_id',session('Default_Program_Id'))
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

    public function getPayoutSummaryDetails(Request $request){
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
                        return '<a href="javascript:void(0)" data-selectedvoucherid="'.$row->voucher_details_id.'" data-toggle="modal" data-target="#ViewHoldTransAttachmentsModal" class="btn btn-outline-info PayoutSummaryAttachments"><span class="fa fa-eye"></span> View Attachments</a>';
                        
                    }else{
                        return '<i class="text-danger">No attachement</i>';
                    }
                    
                })
                // ADD COLUMN FOR GRAND TOTAL
                ->addColumn('grandtotalamount', function($row){
                    $getgrandtotal = DB::table('payout_gif_batch as pgb')
                    ->select(DB::raw("sum(pgb.amount) as grantotal"))
                    ->where('pgb.batch_id',$row->batch_id)
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

    public function getAttachmentsImg(Request $request){
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
}
