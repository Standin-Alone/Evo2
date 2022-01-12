<?php

namespace App\Modules\VoucherTrans\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\VoucherTrans\Models\VoucherTrans;

class VoucherTransController extends Controller
{
    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(Request $request)
    {
        $this->QueryVoucherTrans = new VoucherTrans();
    }

    public function index(){
        if (!empty(session('uuid'))) {
            return view("VoucherTrans::index");
        }else{
            return redirect('/login');
        }
    }
    
    public function getVoucherTransList(Request $request){
        if ($request->ajax()) {
            $getData = DB::table('vw_claimed_voucher_items as vt')
                // ->where('vt.supplier_id',session('uuid'))
                // ->where('vt.program_id',session('Default_Program_Id'))
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
                        return '<a href="javascript:void(0)" data-selectvoucherid="'.$row->voucher_details_id.'" class="btn btn-xs btn-outline-info btnViewVoucherAttachments"><span class="fa fa-eye"></span> VIEW ATTACHMENT</a>';
                        
                    }else{
                        return '<i class="text-danger"><span class="fa fa-ban"></span> No Attachment</i>';
                    }
                })                
                ->addColumn('grandtotalamount', function($value){
                    $getgrandtotal = DB::table('vw_claimed_voucher_items as vt')
                    ->select(DB::raw("sum(total_amount) as grantotal"))
                    ->where('vt.supplier_id',session('uuid'))
                    ->where('vt.program_id',session('Default_Program_Id'))
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

    public function getVoucherListAttachments(Request $request){        
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
