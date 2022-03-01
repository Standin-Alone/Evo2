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
        if (!empty(session('uuid'))) {
            return view("SupplierPayout::index");
         }else{
            return redirect('/login');
        }  
    }

    public function getSupplierPayout(Request $request){
        $reg_code = sprintf("%02d", session('reg_code'));
        if ($request->ajax()) {
            $viewData = DB::table('payout_gfi_details as pgd')
            ->select(DB::raw("pgd.payout_id,pgd.batch_id,DATE_FORMAT(pgd.transac_date, '%M %d, %Y %H:%i %p') as transac_date,pgb.application_number,pgb.description,pgb.amount,pgb.issubmitted,
                            pgb.supplier_id,pgb.ishold,pgb.program_id,p.description as  program,count(voucher_details_id) as cnt_vouchers"))
            ->leftJoin('payout_gif_batch as pgb', 'pgd.batch_id', '=', 'pgb.batch_id')
            ->leftJoin('programs as p', 'p.program_id', '=', 'pgb.program_id')
            ->where(function ($query) use($reg_code){
                if($reg_code != '13'){
                    $query->where('pgb.supplier_id',session('uuid'))
                    ->where('pgb.program_id',session('Default_Program_Id'));
                }
            })
            // ->where('isremove',0)
            ->groupBy('pgd.batch_id')
            ->get();            
            return Datatables::of($viewData)->addIndexColumn()->make(true);
        }
    }

    public function getSupplierPayoutContent(){

        $getData = DB::table('payout_gfi_details as pgd')
        ->select(DB::raw("pgd.payout_id,pgd.batch_id,pgd.transac_date,pgb.application_number,pgb.description,pgb.amount,pgb.issubmitted,
                        pgb.supplier_id,pgb.ishold,pgb.program_id,p.description as  program"))
        ->leftJoin('payout_gif_batch as pgb', 'pgd.batch_id', '=', 'pgb.batch_id')
        ->leftJoin('programs as p', 'p.program_id', '=', 'pgb.program_id')
        ->where('pgb.supplier_id',session('uuid'))
        ->where('pgb.program_id',session('Default_Program_Id'))
        // ->where('isremove',0)
        ->groupBy('pgd.batch_id')
        ->get();

        return response()->json($getData);    
        
        
    }

    public function getBatchPayoutDropList(){
        $getBatchPayout = DB::table('payout_gif_batch as pgb')
        ->select(DB::raw("pgb.batch_id,pgb.application_number"))
        ->where('pgb.supplier_id',session('uuid'))
        ->where('pgb.program_id',session('Default_Program_Id'))
        ->where('pgb.issubmitted','=',"0")
        ->orderBy('pgb.transac_date')
        ->get();
        return response()->json($getBatchPayout);
    }

    public function getSupplierClaimedVoucher(Request $request){
        if ($request->ajax()) {
            $viewData = DB::table('vw_claimed_voucher_items as vt')
            ->where('vt.supplier_id',session('uuid'))
            ->where('vt.program_id',session('Default_Program_Id'))
            // ->where('vt.payout','0')
            ->where('vt.batch_id','=',NULL)
            ->where('vt.ishold','=',0)
            // ->where('isremove',0)
            ->get();

            return Datatables::of($viewData)->make(true);
        }
    }

    public function getSupplierPayout_ViewTotalDetails(Request $request){
        $TotalDetails_id = $request->TotalDetails_id;
        // dd($TotalDetails_id);
        if ($request->ajax()) {
            $viewData = DB::table('vw_claimed_voucher_items as vt')
            ->where('vt.supplier_id',session('uuid'))
            ->where('vt.program_id',session('Default_Program_Id'))            
            // ->whereNotNull('vt.batch_id')
            ->where(function ($query) use ($TotalDetails_id){
                // if($TotalDetails_id == "TotalClaimedVoucher"){
                //     $query->where('vt.payout','0')
                //         ->where('vt.issubmitted','0')
                //         ->where('vt.isremove','0');
                //     }
                // else 
                if($TotalDetails_id == "TotalPendingPayouts"){
                    $query->where('vt.payout','0')
                        ->where('vt.isremove');
                }
                else if($TotalDetails_id == "TotalApprovedPayouts"){
                    $query->where('vt.payout','1')
                        ->where('vt.isremove','0');
                }
                else{
                    $query->where('isremove','0');
                }
            })
            ->get();

            return Datatables::of($viewData)->make(true);
        }
    }

    public function getSupplierPayout_TotalClaimedVoucher(){
        $viewData = DB::table('vw_claimed_voucher_items as vt')
            ->select(DB::raw("sum(vt.total_amount) as amount"))
            ->where('vt.supplier_id',session('uuid'))
            ->where('vt.program_id',session('Default_Program_Id')) 
            ->where('vt.batch_id','=',NULL)
            ->where('vt.ishold','=',0)
            ->get();

            return response()->json($viewData);  
    }

    public function getSupplierPayout_TotalPendingPayouts(){
        $viewData = DB::table('vw_claimed_voucher_items as vt')
            ->select(DB::raw("sum(vt.total_amount) as amount"))
            ->where('vt.supplier_id',session('uuid'))
            ->where('vt.program_id',session('Default_Program_Id'))            
            ->where('vt.payout_endorse_approve','0')
            ->where('vt.isremove','0')
            ->get();

            return response()->json($viewData); 
    }

    public function getSupplierPayout_TotalApprovedPayouts(){
        $viewData = DB::table('vw_claimed_voucher_items as vt')
            ->select(DB::raw("sum(vt.total_amount) as amount"))
            ->where('vt.supplier_id',session('uuid'))
            ->where('vt.program_id',session('Default_Program_Id'))            
            ->where('vt.payout_endorse_approve','1')
            ->where('vt.isremove','0')
            ->get();

            return response()->json($viewData); 
    }
    
    public function getSupplierPayout_TotalHoldVoucher(){
        $viewData = DB::table('vw_claimed_voucher_items as vt')
            ->select(DB::raw("sum(vt.total_amount) as amount"))
            ->where('vt.supplier_id',session('uuid'))
            ->where('vt.program_id',session('Default_Program_Id'))            
            ->where('isremove','1')
            ->get();

            return response()->json($viewData); 
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
                // ->addIndexColumn()
                // ->addColumn('action', function($row){    
                //     return '<a href="javascript:void(0)" data-selectvoucherid="'.$row->voucher_details_id.'" class="btn btn-xs btn-outline-info btnViewSupplierPayoutVoucherAttachments"><span class="fa fa-eye"></span><i class="fas fa-spinner fa-spin '.$row->voucher_details_id.' pull-left m-r-10" style="display: none;"></i> View Attachments</a>';                              
                // })
                // ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getHoldTransAttachmentsImg(Request $request){        
        $getVoucherAttachmentsImg = DB::table('vw_claimed_voucher_att as va')
            ->where('va.voucher_details_id','=',$request->voucher_id)
            ->get();

            $output = [];        
            
            if(!($getVoucherAttachmentsImg)){
                foreach ($getVoucherAttachmentsImg as $key => $imgview) {
                    
                    try {
                        if($key == 0){                
                            array_push($output,'<div class="carousel-item active">
                            <img id="VoucherAttachmentsImage'.$key.'" src="data:image/jpeg;base64,'.base64_encode(file_get_contents(('uploads'.'/'.'transactions'.'/'.'attachments'.'/'.session('Default_Program_shortname').'/'.$imgview->transac_year.'/' . $imgview->rsbsa_no.'/'.$imgview->file_name.'.png'))).'" alt="First slide" style="width:100%; height:auto;">
                                <div class="carousel-caption d-none d-md-block">
                                    <p style="color:gray;">'.$imgview->document.'</p>
                                    <h5>'.$imgview->file_name.'</h5>                                
                                </div>
                            </div>');
                        }else{
                            array_push($output,'<div class="carousel-item ">
                            <img id="VoucherAttachmentsImage'.$key.'" src="data:image/jpeg;base64,'.base64_encode(file_get_contents(('uploads'.'/'.'transactions'.'/'.'attachments'.'/'.session('Default_Program_shortname').'/'.$imgview->transac_year.'/' . $imgview->rsbsa_no.'/'.$imgview->file_name.'.png'))).'" alt="First slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <p style="color:gray;">'.$imgview->document.'</p>
                                    <h5>'.$imgview->file_name.'</h5>                                
                                </div>
                            </div>');
                        }
                    } catch (\Throwable $th) {
                        $output = '
                        <div class="carousel-item active">
                            <img id="VoucherAttachmentsImage" src="data:image/jpg;base64,'.base64_encode(file_get_contents(asset('assets/img/gallery/noimage.png'))).'" alt="First slide">
                        </div>';
                    }
                } 
            }else{
               
                $output = '
                    <div class="carousel-item active">
                        <img id="VoucherAttachmentsImage" src="data:image/jpg;base64,'.base64_encode(file_get_contents(asset('assets/img/gallery/noimage.png'))).'" alt="First slide">
                    </div>';
            }
            return $output;
    }

    public function saveSupplierPayout(Request $request){
        $CurrentYear = Carbon::now('GMT+8')->format('Y');
        $CurrentProgram = session('Default_Program_shortname');
        $AppNumSeries = "";
        $total_amount = 0;
        $transaction_date = Carbon::now('GMT+8');
        $batch_id = Uuid::uuid4();
        $getData = DB::table('payout_gif_batch as pgb')
                ->select(DB::raw("(CASE WHEN MAX(RIGHT(concat('0000000000',right(application_number,10)+1),10)) IS NULL THEN '0000000001'
                ELSE MAX(RIGHT(concat('0000000000',right(application_number,10)+1),10)) END) as appnumseries"))
                ->get();
            foreach ($getData as $key => $value) {
                $AppNumSeries = 'DA'.$CurrentProgram.$CurrentYear.$value->appnumseries;
            }

        DB::table('payout_gif_batch')->insert([
            'batch_id'=>$batch_id,
            'application_number'=>$AppNumSeries,
            'bank_name'=>session('Supplier_Bank_name'),
            'supplier_id'=>session('uuid'),
            'amount'=>0,
            'transac_date'=>$transaction_date,
            'description'=>"",
            'program_id'=>session('Default_Program_Id')
        ]);

        $getClaimedVouchers = DB::table('vw_claimed_voucher_items as vt')
            ->where('vt.supplier_id',session('uuid'))
            ->where('vt.program_id',session('Default_Program_Id')) 
            ->where('vt.batch_id','=',NULL)
            ->where('vt.ishold','=',0)
            ->where('vt.isremove','=',0)
            ->get();

        foreach ($getClaimedVouchers as $key => $value) {
            $selectedid = $value->voucher_details_id;
            $total_amount += $value->total_amount;
            // SAVE PER VOUCHER FOR PAYOUT ON PAYOUT_GFI_DETAILS TABLE
            DB::table('payout_gfi_details')->insert([
                'payout_id'=>Uuid::uuid4(),
                'voucher_details_id'=>$selectedid,
                'batch_id'=>$batch_id,
                'transac_date'=>$transaction_date,
                'transac_agency'=>null,
                'transac_by_id'=>session('uuid'),
                'transac_by_fullname'=>session('user_fullname')
            ]); 

            DB::table('voucher_transaction')
                ->where('voucher_details_id', $selectedid)
                ->update(['batch_id' => $batch_id]);            
        }
        // UPDATE PAYOUT_GIF_BATCH TABLE FOR TOTAL AMOUNT OF VOUCHERS BASED ON SELECTED BATCH
        DB::table('payout_gif_batch')
            ->where('batch_id', $batch_id)
            ->update(['amount' => DB::raw('amount + '.$total_amount)]);  

        return "saved";        
    }

    
    public function SubmitSupplierPayout(Request $request){
        return DB::table('payout_gif_batch')
        ->where('batch_id', $request->batch_id)
        ->update(['issubmitted' => 1]);
    }

    public function removeClaimedVoucher(Request $request){
        return DB::table('voucher_transaction')
        ->where('voucher_details_id', $request->voucher_id)
        ->update(['isremove' => 1, 'date_removed'=>Carbon::now('GMT+8'), 'removed_by_id'=> session('user_id'), 'removed_by_name'=>session('user_fullname')]);
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
