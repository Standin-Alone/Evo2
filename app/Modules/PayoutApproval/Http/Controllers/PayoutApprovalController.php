<?php

namespace App\Modules\PayoutApproval\Http\Controllers;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\PayoutApproval\Models\PayoutApproval;

class PayoutApprovalController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!empty(session('uuid'))) {
            return view("PayoutApproval::index");
        }else{
            return redirect('/login');
        }
    }

    public function getPayoutApprovalList(Request $request){
        if ($request->ajax()) {
            $getData = DB::table('payout_gfi_details as pgd')
            ->select(DB::raw("pgd.payout_id,pgd.batch_id,pgd.transac_date,pgb.application_number,pgb.description,pgb.amount,pgb.issubmitted,p.description as program"))
            ->leftJoin('payout_gif_batch as pgb', 'pgd.batch_id', '=', 'pgb.batch_id')
            ->leftJoin('supplier_programs as sp', 'pgb.supplier_id', '=', 'sp.supplier_id')
            ->leftJoin('programs as p', 'p.program_id', '=', 'pgb.program_id')
            ->where('p.program_id',session('Default_Program_Id'))
            ->where('pgb.issubmitted',"1")
            ->where('pgb.payout_endorse_approve',"0")
            ->groupBy("pgd.batch_id")
            ->get();
            return Datatables::of($getData)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '<a href="javascript:void(0)" data-selectedbatchid="'.$row->batch_id.'" class="btn btn-xs btn-outline-info btnViewPayoutApprovalDetails"><span class="fa fa-eye"></span><i class="fas fa-spinner fa-spin '.$row->batch_id.' pull-left m-r-10" style="display: none;"></i> View Details</a>';
                })
                ->addColumn('checkbox', function($row){
                    return '
                        <div class="checkbox checkbox-css">
                            <input type="checkbox" id="cssCheckbox1" data-selectedbatchid="'.$row->batch_id.'" data-selectedbatchamt="'.$row->amount.'" class="selectedbatch" />
                            <label for="cssCheckbox1"></label>
                        </div>';                                        
                })
                ->rawColumns(['action','checkbox'])
                ->make(true);
        }
    }

    public function getPayoutApprovalDetails(Request $request){
        if ($request->ajax()) {
            $getData = DB::table('vw_claimed_voucher_items as vt')
                ->where('vt.batch_id',$request->batch_id)
                ->groupBy('vt.voucher_details_id')
                ->get();

                return Datatables::of($getData)
                ->addIndexColumn()
                ->addColumn('option', function($row){    
                    if($row->ishold == 0){
                        $viewData = DB::table('voucher_attachments')
                            ->select(DB::raw("attachment_id"))
                            ->where('voucher_details_id','=',$row->voucher_details_id)
                            ->exists();
                        if($viewData){
                            return '<a href="javascript:void(0)" data-selectvoucherid="'.$row->voucher_details_id.'" class="btn btn-xs btn-outline-info btnViewVoucherAttachments"><span class="fa fa-eye"></span><i class="fas fa-spinner fa-spin '.$row->batch_id.' pull-left m-r-10" style="display: none;"></i> View Attachments</a>';                           
                        }else{
                            return '<i class="text-danger">No attachement</i>';
                        }                        
                    }else{
                        return '<a class="edit btn btn-sm btn-default" style="pointer-events: none;"><span class="fa fa-eye"></span> View Attachements</a>';
                    } 
                })
                ->addColumn('action', function($row1){ 
                    if($row1->ishold == 0){
                        return '<a href="javascript:void(0)" data-selectbatchid="'.$row1->batch_id.'" data-selectvoucherid="'.$row1->voucher_details_id.'" class="btn btn-xs btn-outline-danger btnVoucherHold"><span class="fa fa-exclamation-triangle"></span><i class="fas fa-spinner fa-spin '.$row1->voucher_details_id.' pull-left m-r-10" style="display: none;"></i> Hold</a>';
                    }else{
                        return '<a class="edit btn btn-sm btn-default" style="pointer-events: none;"><span class="fa fa-exclamation-triangle"></span> Hold</a>';
                    }                    
                })                
                ->rawColumns(['option','action'])
                ->make(true);
        }   
    }

    public function getApprovalHistoryDetails(Request $request){        
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

    public function getVoucherAttachmentsImg(Request $request){        
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

    public function holdSelectedVoucher(Request $request){
        DB::table('payout_gif_batch')        
        ->where('batch_id', $request->batch_id)
        ->update(['ishold' => 1]);        

        DB::table('voucher_transaction')
        ->where('voucher_details_id', $request->voucher_id)
        ->update(['ishold' => 1,'date_hold' => Carbon::now('GMT+8'),'remarks' => $request->remarks]);

        return DB::table('holdtrans_logs')->insert([
            'transac_id'=>Uuid::uuid4(),
            'transac_date'=>Carbon::now('GMT+8'),  
            'voucher_details_id'=>$request->voucher_id, 
            'batch_id'=>$request->batch_id, 
            'hold_by_id'=>session('user_id'),                
            'hold_by_fullname'=>session('user_fullname'),
            'status'=>'hold'                            
        ]);

    }

    public function approve1SelectedBatch(Request $request){
        $batchid = explode(",",$request->batchid);

        // GET THE PER BATCH TO SAVE ROW BY ROW
        for($i = 0; $i < count($batchid); ++$i) {

            // CHECK IF VOUCHER TRANSACTION IS HOLD
            $getData = DB::table('vw_claimed_voucher_items as vt')
                ->where('vt.batch_id',$batchid[$i])
                ->where('vt.ishold',1)
                ->exists();
                if($getData){
                    return "Hold";
                }else{
                    // SAVE PER BATCH FOR PAYOUT ON PAYOUT_APPROVAL_LOG TABLE
                    $query = DB::table('payout_approval_log')->insert([
                        'log_id'=>Uuid::uuid4(),
                        'batch_id'=>$batchid[$i],                
                        'approved_agency'=>session('reg_code'),
                        'approved_by_id'=>session('user_id'),
                        'approved_by_fullname'=>session('user_fullname'),
                        'description'=>$request->description,
                        'approval_date'=>Carbon::now('GMT+8')                
                    ]);
                    if($query){
                        DB::table('payout_gif_batch')
                        ->where('batch_id', $batchid[$i])
                        ->update(['payout_endorse_approve' => 1]);    
                    
                    }
                }
        }        
        return view("PayoutApproval::index");        
    }

    public function getHoldTransactionDetails(Request $request){   
        if ($request->ajax()) {
            $getData = DB::table('vw_claimed_voucher_items as vt')
                ->where('vt.ishold',1)
                ->where('vt.date_hold',"<>",'')
                ->groupBy('vt.voucher_details_id')
                ->get();

                return Datatables::of($getData)
                ->addIndexColumn()
                ->addColumn('option', function($row){    
                    if($row->ishold == 1){
                        $viewData = DB::table('voucher_attachments')
                            ->select(DB::raw("attachment_id"))
                            ->where('voucher_details_id','=',$row->voucher_details_id)
                            ->exists();
                        if($viewData){
                            return '<a href="javascript:void(0)" data-selectbatchid="'.$row->batch_id.'" data-selectvoucherid="'.$row->voucher_details_id.'" class="btn btn-xs btn-outline-info btnViewVoucherAttachments"><span class="fa fa-eye"></span><i class="fas fa-spinner fa-spin '.$row->voucher_details_id.' pull-left m-r-10" style="display: none;"></i> View Attachment</a>';
                            
                        }else{
                            return '<i class="text-danger">No attachement</i>';
                        }
                        
                    }else{
                        return '<a class="edit btn btn-sm btn-default" style="pointer-events: none;"><span class="fa fa-eye"></span> View</a>';
                    }
                })
                ->addColumn('action', function($value){ 
                    if($value->ishold == 1){
                        return '<a href="javascript:void(0)" data-selectbatchid="'.$value->batch_id.'" data-selectvoucherid="'.$value->voucher_details_id.'" class="btn btn-xs btn-outline-info btnActivateHoldTrans" style="width:80px;"><span class="fa fa-check-circle"></span><i class="fas fa-spinner fa-spin '.$row->voucher_details_id.' pull-left m-r-10" style="display: none;"></i> Activate</a>';
                    }else{
                        return '<a class="edit btn btn-sm btn-default" style="pointer-events: none;"><span class="fa fa-check-circle" style="width:80px;"></span> Hold</a>';
                    } 
                })                
                ->rawColumns(['option','action'])
                ->make(true);
        }   
    }

}
