<?php

namespace App\Modules\HoldVoucherHistory\Http\Controllers;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class HoldVoucherHistoryController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if (!empty(session('user_id'))) {
            return view("HoldVoucherHistory::index");
        }else{
            return redirect('/login');
        }
    }

    public function getHoldVoucherHistoryList(Request $request){   
        if ($request->ajax()) {
            $getData = DB::table('vw_claimed_voucher_items as vt')
                ->where('vt.program_id',session('Default_Program_Id'))
                ->where('vt.ishold',1)
                ->where('vt.date_hold',"<>",null)
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
                        return '<a href="javascript:void(0)" data-selectbatchid="'.$value->batch_id.'" data-selectvoucherid="'.$value->voucher_details_id.'" class="btn btn-xs btn-outline-info btnActivateHoldTrans"><i class="fas fa-spinner fa-spin '.$value->voucher_details_id.' pull-left m-r-10" style="display: none;"></i><span class="fa fa-check-circle"></span> Activate</a>';
                    }else{
                        return '<a class="edit btn btn-sm btn-default" style="pointer-events: none;"><span class="fa fa-check-circle" style="width:80px;"></span> Hold</a>';
                    }                   
                    
                    
                })
                ->addColumn('grandtotalamount', function($row){
                    $getgrandtotal = DB::table('vw_claimed_voucher_items as vt')
                    ->select(DB::raw("sum(total_amount) as grantotal"))   
                    ->where('vt.program_id',session('Default_Program_Id'))                               
                    ->where('vt.ishold',1)
                    ->where('vt.date_hold',"<>",null)                    
                    ->get();
                    $grandtotal=0;  
                    foreach ($getgrandtotal as $key => $value) {
                        $grandtotal += $value->grantotal;   
                    }                                     
                    return $grandtotal;
                })
                
                ->rawColumns(['option','action','grandtotalamount'])
                ->make(true);
        }   
    }

    public function getHoldVoucherHistoryAttachmentsImg(Request $request){
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

    public function activateHoldVoucherTransaction(Request $request){
        
        DB::table('payout_gif_batch')        
        ->where('batch_id', $request->batch_id)
        ->update(['ishold' => 0]); 

        DB::table('voucher_transaction')
        ->where('voucher_details_id', $request->voucher_id)
        ->update(['ishold' => 0,'date_hold' => null,'remarks' => '']);   

        return DB::table('holdtrans_logs')->insert([
            'transac_id'=>Uuid::uuid4(),
            'transac_date'=>Carbon::now('GMT+8'),  
            'voucher_details_id'=>$request->voucher_id, 
            'batch_id'=>$request->batch_id, 
            'hold_by_id'=>session('user_id'),                
            'hold_by_fullname'=>session('user_fullname'),
            'status'=>'activated'                   
        ]);
        
             
    }


}
