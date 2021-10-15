<?php

namespace App\Modules\SupplierPayout\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\SupplierPayout\Models\SupplierPayout;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class SupplierPayoutController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if (!empty(session('supplier_id'))) {
            return view("SupplierPayout::index");
         }else{
            return redirect('/login');
        }  
    }

    public function getSupplierPayout(Request $request){
        if ($request->ajax()) {
            $viewData = DB::table('payout_gfi_details as pgd')
            ->select(DB::raw("pgd.payout_id,pgd.batch_id,pgd.transac_date,pgb.application_number,pgb.description,pgb.amount,pgb.issubmitted,
                            pgb.supplier_id,pgb.ishold,pgb.program_id,p.description as  program"))
            ->leftJoin('payout_gif_batch as pgb', 'pgd.batch_id', '=', 'pgb.batch_id')
            ->leftJoin('programs as p', 'p.program_id', '=', 'pgb.program_id')
            ->where('pgb.supplier_id',session('supplier_id'))
            ->where('pgb.program_id',session('Default_Program_Id'))
            ->groupBy('pgd.batch_id')
            ->get();            
            return Datatables::of($viewData)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if($row->issubmitted == 0){
                        if($row->ishold == 1){
                            return '
                                <a href="javascript:void(0)" data-removesupplierbatchid="'.$row->batch_id.'" class="btn btn-xs btn-outline-danger btnRemoveSupplierPayout"><span class="fa fa-trash"></span><i class="fas fa-spinner fa-spin '.$row->batch_id.' pull-left m-r-10" style="display: none;"></i> Remove</a>                    
                                <a href="javascript:void(0)" data-submitsupplierbatchid="'.$row->batch_id.'" class="btn btn-xs btn-outline-success btnSubmitSupplierPayout"><span class="fa fa-check-circle"></span><i class="fas fa-spinner fa-spin '.$row->batch_id.' pull-left m-r-10" style="display: none;"></i> Submit</a>
                                <a href="javascript:void(0)" data-submitsupplierbatchid="'.$row->batch_id.'" class="btn btn-xs btn-outline-info btnViewSupplierPayoutDetails"><span class="fa fa-eye"></span><i class="fas fa-spinner fa-spin '.$row->batch_id.' pull-left m-r-10" style="display: none;"></i> View Details</a>
                                <a href="javascript:void(0)" data-holtransactionbatchid="'.$row->batch_id.'" class="btn btn-xs btn-outline-danger btnViewHoldtransDetails"><span class="fa fa-exclamation-triangle"></span><i class="fas fa-spinner fa-spin '.$row->batch_id.' pull-left m-r-10" style="display: none;"></i> View Hold</a>';
                        }else{
                            return '
                                <a href="javascript:void(0)" data-removesupplierbatchid="'.$row->batch_id.'" class="btn btn-xs btn-outline-danger btnRemoveSupplierPayout"><span class="fa fa-trash"></span><i class="fas fa-spinner fa-spin '.$row->batch_id.' pull-left m-r-10" style="display: none;"></i> Remove</a>                    
                                <a href="javascript:void(0)" data-submitsupplierbatchid="'.$row->batch_id.'" class="btn btn-xs btn-outline-success btnSubmitSupplierPayout"><span class="fa fa-check-circle"></span><i class="fas fa-spinner fa-spin '.$row->batch_id.' pull-left m-r-10" style="display: none;"></i> Submit</a>
                                <a href="javascript:void(0)" data-submitsupplierbatchid="'.$row->batch_id.'" class="btn btn-xs btn-outline-info btnViewSupplierPayoutDetails"><span class="fa fa-eye"></span><i class="fas fa-spinner fa-spin '.$row->batch_id.' pull-left m-r-10" style="display: none;"></i> View Details</a>';
                        }   
                        
                    }else{
                        if($row->ishold == 1){
                            return '
                                <a class="btn btn-xs" style="pointer-events: none;"><span class="fa fa-trash"></span> Remove</a>
                                <a class="btn btn-xs" style="pointer-events: none;"><span class="fa fa-check-circle"></span> Submit</a>
                                <a href="javascript:void(0)" data-submitsupplierbatchid="'.$row->batch_id.'" class="btn btn-xs btn-outline-info backend-style btnViewSupplierPayoutDetails"><span class="fa fa-eye"></span><i class="fas fa-spinner fa-spin '.$row->batch_id.' pull-left m-r-10" style="display: none;"></i> View Details</a>
                                <a href="javascript:void(0)" data-holtransactionbatchid="'.$row->batch_id.'" class="btn btn-xs btn-outline-danger btnViewHoldtransDetails"><span class="fa fa-exclamation-triangle"></span><i class="fas fa-spinner fa-spin '.$row->batch_id.' pull-left m-r-10" style="display: none;"></i> View Hold</a>';
                        }else{
                            return '
                                <a class="btn btn-xs" style="pointer-events: none;"><span class="fa fa-trash"></span> Remove</a>
                                <a class="btn btn-xs" style="pointer-events: none;"><span class="fa fa-check-circle"></span> Submit</a>
                                <a href="javascript:void(0)" data-submitsupplierbatchid="'.$row->batch_id.'" class="btn btn-xs btn-outline-info backend-style btnViewSupplierPayoutDetails"><span class="fa fa-eye"></span><i class="fas fa-spinner fa-spin '.$row->batch_id.' pull-left m-r-10" style="display: none;"></i> View Details</a>';
                        }
                        
                    }
                })
                ->addColumn('totalpending', function($data){
                    $getgrandtotal = DB::table('payout_gfi_details as pgd')
                    ->select(DB::raw("(pgb.amount) as grantotal"))
                    ->leftJoin('payout_gif_batch as pgb', 'pgd.batch_id', '=', 'pgb.batch_id')
                    ->where('pgb.supplier_id',session('supplier_id'))
                    ->where('pgb.program_id',session('Default_Program_Id'))
                    ->where('pgb.issubmitted',"1")
                    ->where('pgb.payout_endorse_approve',"0")
                    ->groupBy('pgd.batch_id')
                    ->get();
                    $grandtotal=0;  
                    foreach ($getgrandtotal as $key => $value) {
                        $grandtotal += $value->grantotal;   
                    }                                     
                    return $grandtotal;
                })
                ->addColumn('grandtotalamount', function($data){
                    $getgrandtotal = DB::table('payout_gfi_details as pgd')
                    ->select(DB::raw("(pgb.amount) as grantotal"))
                    ->leftJoin('payout_gif_batch as pgb', 'pgd.batch_id', '=', 'pgb.batch_id')
                    ->where('pgb.supplier_id',session('supplier_id'))
                    ->where('pgb.program_id',session('Default_Program_Id'))
                    ->groupBy('pgd.batch_id')
                    ->get();
                    $grandtotal=0;  
                    foreach ($getgrandtotal as $key => $value) {
                        $grandtotal += $value->grantotal;   
                    }                                     
                    return $grandtotal;
                })               
                ->rawColumns(['action','totalpending','grandtotalamount'])
                ->make(true);
        }
    }

    public function getBatchPayoutDropList(){
        $getBatchPayout = DB::table('payout_gif_batch as pgb')
        ->select(DB::raw("pgb.batch_id,pgb.application_number"))
        ->where('pgb.supplier_id',session('supplier_id'))
        ->where('pgb.program_id',session('Default_Program_Id'))
        ->where('pgb.issubmitted','=',"0")
        ->orderBy('pgb.transac_date')
        ->get();
        return response()->json($getBatchPayout);
    }

    public function getSupplierClaimedVoucher(Request $request){
        $param = "";
        if(empty($request->batch_id)){
            $param = "=";
        }else{
            $param = "<>";
        }
        if ($request->ajax()) {
            $viewData = DB::table('vw_claimed_voucher_items as vt')
            ->where('vt.supplier_id',session('supplier_id'))
            ->where('vt.program_id',session('Default_Program_Id'))
            ->where('vt.batch_id','')
            ->where('vt.batch_id',$param,$request->batch_id)
            ->get();

            return Datatables::of($viewData)
                ->addIndexColumn()
                ->addColumn('checkbox', function($row){
                    return '
                    <div class="checkbox checkbox-css">
                        <input type="checkbox" id="cssCheckbox1" data-selectedvoucherid="'.$row->voucher_details_id.'" data-selectedvoucheramt="'.$row->total_amount.'" class="selectedvoucher" />
                        <label for="cssCheckbox1"></label>
                    </div>
                    ';
                           
                })
                ->rawColumns(['checkbox'])
                ->make(true);
        }
    }

    public function getBatchpayoutdetails(Request $request){
        if ($request->ajax()) {
            $viewData = DB::table('vw_claimed_voucher_items as vt')
                ->where('vt.batch_id',$request->batch_id)
                ->get();
            
            return Datatables::of($viewData)
                // ADD COLUMN FOR GRAND TOTAL
                ->addColumn('grandtotalamount', function($value){
                    $getgrandtotal = DB::table('voucher_transaction as vt')
                    ->select(DB::raw("sum(total_amount) as grantotal"))
                    ->where('vt.batch_id',$value->batch_id)
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

    public function getHoldTransactionDetails(Request $request){
        if ($request->ajax()) {
            $viewData = DB::table('vw_claimed_voucher_items as vt')
                ->where('vt.batch_id',$request->batch_id)
                ->where('vt.ishold',"1")                
                ->get();
            
            return Datatables::of($viewData)
                ->addIndexColumn()
                ->addColumn('action', function($row){    
                    return '<a href="javascript:void(0)" data-selectvoucherid="'.$row->voucher_details_id.'" class="btn btn-xs btn-outline-info btnViewSupplierPayoutVoucherAttachments"><span class="fa fa-eye"></span><i class="fas fa-spinner fa-spin '.$row->voucher_details_id.' pull-left m-r-10" style="display: none;"></i> View Attachments</a>';                              
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getHoldTransAttachmentsImg(Request $request){        
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

    public function saveSupplierPayout(Request $request){
        
        // CONVERT INTO ARRAY FORM
        $selectedid = explode(",",$request->selectedvoucherid);
        $selectedamt = explode(",",$request->selectedvoucheramt);        
        $total_amount = 0;

        // GET THE TOTAL SELECTED VOUCHER'S AMOUNT
        for($i = 0; $i < count($selectedamt); ++$i) {
            $total_amount += $selectedamt[$i];
        }        

        // GET THE PER VOUCHER TO SAVE ROW BY ROW
        for($i = 0; $i < count($selectedid); ++$i) {

            // SAVE PER VOUCHER FOR PAYOUT ON PAYOUT_GFI_DETAILS TABLE
            $query = DB::table('payout_gfi_details')->insert([
                'payout_id'=>Uuid::uuid4(),
                'voucher_details_id'=>$selectedid[$i],
                'batch_id'=>$request->batch_id,
                'transac_date'=>Carbon::now('GMT+8'),
                'transac_agency'=>null,
                'transac_by_id'=>null,
                'transac_by_fullname'=>null
            ]);  
            if(!empty($query)){
                DB::table('voucher_transaction')
                ->where('voucher_details_id', $selectedid[$i])
                ->update(['batch_id' => $request->batch_id]);
            }          
        }

        // UPDATE PAYOUT_GIF_BATCH TABLE FOR TOTAL AMOUNT OF VOUCHERS BASED ON SELECTED BATCH
        DB::table('payout_gif_batch')
        ->where('batch_id', $request->batch_id)
        ->update(['amount' => DB::raw('amount + '.$total_amount)]);   
    }

    
    public function SubmitSupplierPayout(Request $request){
        return DB::table('payout_gif_batch')
        ->where('batch_id', $request->batch_id)
        ->update(['issubmitted' => 1]);
    }

    public function RemoveSupplierPayout(Request $request){
        $removeBatchPayout = DB::table('payout_gfi_details')
        ->where('batch_id', $request->batch_id)
        ->delete();
        if(!empty($removeBatchPayout)){
            DB::table('payout_gif_batch')
            ->where('batch_id', $request->batch_id)
            ->update(['amount' => 0]);

            return DB::table('voucher_transaction')
            ->where('batch_id', $request->batch_id)
            ->update(['batch_id' => null]);
        }        
    }

    public function getStatusBatchPayout(Request $request){
        // return dd($request->app_num);
        return json_encode(
            DB::table('payout_gif_batch as pgb')
            ->where('pgb.application_number','=',$request->app_num)
            ->get());                    
    }
    
}
