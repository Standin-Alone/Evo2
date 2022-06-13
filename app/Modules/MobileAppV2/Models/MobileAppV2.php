<?php

namespace App\Modules\MobileAppV2\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use File;
use Mail;
use DB;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Support\Arr;


class MobileAppV2 extends Model
{
    use HasFactory;

    public function get_transacted_vouchers(){


        try{
            $supplier_id = request('supplierId');
            $offset = request('page');
            $get_transacted_vouchers = db::table('voucher as v')
                ->select(
                    'v.reference_no',
                    'transac_date',
                    DB::raw("CONCAT(first_name,' ',middle_name,' ',last_name) as fullname"),
                    'rsbsa_no',
                    'file_name',
                    'v.amount_val as current_balance',
                    'v.amount as default_balance',
                    'vt.voucher_details_id',   
                    'shortname as program',
                    'title as program_title',
                    DB::raw("YEAR(transac_date) as year_transac"),   
                    DB::raw("CONCAT(gm.bgy_name,', ',gm.mun_name,', ',gm.prov_name,', ',gm.reg_name) as address"),
                    'vt.transaction_id'

                )
                ->join('voucher_transaction as vt', 'v.reference_no','vt.reference_no')            
                ->leftJoin('voucher_attachments as va', 'va.transaction_id','vt.transaction_id')
                ->join('programs as p', 'p.program_id','v.program_id')      
                ->join('geo_map as gm', 'gm.geo_code','v.geo_code')           
                ->where('supplier_id', $supplier_id)              
                ->groupBy('v.reference_no')
                ->orderBy('transac_date', 'DESC')     
                ->skip($offset)
                ->take(2)               
                ->get();
            

                 
            foreach ($get_transacted_vouchers as $key => $item) {
                $get_attachments  = db::table('voucher_attachments')
                                        ->where('transaction_id',$item->transaction_id)                                        
                                        ->get();    
                $get_commodities = db::table('program_items as pi')
                                        ->join('supplier_programs as sp','pi.item_id','sp.item_id')    
                                        ->join('voucher_transaction as vt','vt.sub_program_id','sp.sub_id')
                                        ->where('vt.transaction_id',$item->transaction_id)
                                        ->get();

                $image_array = [];

                foreach($get_attachments as $attachment_key => $attachment_item){
                    if(file_exists('uploads/transactions/attachments'.'/'.$item->program.'/'.$item->year_transac.'/' . $item->rsbsa_no.'/'.$attachment_item->file_name)){
                        array_push($image_array,["name"=>$attachment_item->document,"image"=>base64_encode(file_get_contents('uploads/transactions/attachments'.'/'.$item->program.'/'.$item->year_transac.'/' . $item->rsbsa_no.'/'.$attachment_item->file_name))]);                    
                    }else{
                        
                        array_push($image_array,["name"=>$attachment_item->document,"image"=>base64_encode(file_get_contents('public/edcel_images/no-image.jpg'))]);                    
                    }
                    
                }

                
                $item->base64 = $image_array;

                $item->commodities = $get_commodities;

            }        

            
            return response()->json([
                "status"  => true,
                "message" => "Sucessfully loaded the transacted vouchers.",            
                "data"    => $get_transacted_vouchers
            ]); 
        }catch(\Exception $e){

            return response()->json([
                "status"  => false,
                "message" => "Something went wrong!",            
                "errorMessage" => $e->getMessage()
            ]); 
        }
    }


    public function login(){


        try{

            $username = request('username');
            $password = request('password');

            
            $get_user_info = db::table('users as u')
                                    ->select(
                                            'u.user_id',
                                            's.supplier_name',
                                            'r.role',
                                            DB::raw(" CONCAT(first_name,' ',IFNULL(middle_name,''),' ',last_name) as full_name"),
                                            'u.email', 
                                            'u.username',
                                            'u.password',
                                            'approval_status', 
                                            'u.status',
                                            'reg_name'                                        
                                            )
                                    ->join('supplier as s','u.user_id','s.supplier_id')
                                    ->join('program_permissions as pp','u.user_id','pp.user_id')
                                    ->join('roles as r','r.role_id','pp.role_id')
                                    ->join('geo_map as gm','gm.reg_code','u.reg')
                                    ->where('username',$username)->orWhere('u.email',$username)
                                    ->first();

            // check user exist
            if($get_user_info){ 
                
                // check if account is approve
                if($get_user_info->approval_status == '1' && $get_user_info->status == '1'){
                
                    // check if password is correct
                    if(password_verify($password,$get_user_info->password)){

                        $generate_otp = mt_rand(100000, 999999);


                        $store_otp = db::table('user_otp')->updateOrInsert([
                            "user_id" =>$get_user_info->user_id,                      
                        ],["user_id" =>$get_user_info->user_id,"otp" =>$generate_otp,"status" => '1',"date_created" => Carbon::now()]);

                        $email = $get_user_info->email;
                    
                        $get_programs = db::table('program_permissions as pp')
                                            ->join('programs as p','pp.program_id','p.program_id')
                                            ->where('process_type','VOUCHER')
                                            ->where('user_id',$get_user_info->user_id)
                                            ->get();
                        $data_for_email = [
                            "otp_code" => $generate_otp, 
                            "full_name" => $get_user_info->full_name,                             
                            "date" => Carbon::now()->format('M D, Y'), 
                            "role" => $get_user_info->role  
                        ];

                        Mail::send('MobileApp::otp', $data_for_email, function ($message) use ($email) {
                            $message->to($email)
                                    ->subject('OTP');                            
                        });

                        return response()->json([
                            "status"  => true,
                            "message" => "Sucessfully logged in.",            
                            "data"    => $get_user_info,
                            "programs" => $get_programs,
                        ]); 

                    }else{
                        return response()->json([
                            "status"  => false,
                            "message" => "Your password is incorrect.",            
                        ]);     
                    }

                }else{

                    return response()->json([
                        "status"  => false,
                        "message" => "Your account is not yet approved.",            
                    ]);     

                }

            }else{

                return response()->json([
                    "status"  => false,
                    "message" => "Your email doesn't exist.",            
                ]); 

            }         
                  
         }catch(\Exception $e){

            return response()->json([
                "status"  => false,
                "message" => "Something went wrong!",            
                "errorMessage" => $e->getMessage()
            ]); 
        }
    }

    public function verify_otp(){

        try{

            $otp = request('otp');
            $user_id = request('user_id');
            
            $verify_otp = db::table('user_otp')
                            ->where('user_id',$user_id)                        
                            ->orderBy('date_created','desc')
                            ->first();
            

            if($verify_otp->status == '1' ){

                if($verify_otp->otp == $otp){
                    return response()->json([
                        "status"  => true,
                        "message" => "Your OTP is valid."                        
                    ]); 
                }else{
                    return response()->json([
                        "status"  => false,
                        "message" => "Your OTP is incorrect.",            
                    ]); 
                }
            }else{
                return response()->json([
                    "status"  => false,
                    "message" => "Your OTP is expired or invalid.",            
                ]); 
            }

        }catch(\Exception $e){

            return response()->json([
                "status"  => false,
                "message" => "Something went wrong!",            
                "errorMessage" => $e->getMessage()
            ]); 
        }
    }

    
    public function resend_otp(){

        try{

            $user_id = request('user_id');
            $full_name = request('full_name');
            $email = request('email');
            $role = request('role');

            $generate_otp = mt_rand(100000, 999999);


            $store_otp = db::table('user_otp')->updateOrInsert([
                "user_id" =>$user_id,                      
            ],["user_id" =>$user_id,"otp" =>$generate_otp,"status" => '1',"date_created" => Carbon::now()]);

            $data_for_email = [
                "otp_code"  => $generate_otp, 
                "full_name" => $full_name , 
                "date"      => Carbon::now()->format('M D, Y'), 
                "role"      => $role 
            ];

            Mail::send('MobileApp::otp', $data_for_email, function ($message) use ($email) {
                $message->to($email)
                        ->subject('OTP');                            
            });

            return response()->json([
                "status"  => true,
                "message" => "A new OTP has been sent to your email.",            
            ]); 

        }catch(\Exception $e){

            return response()->json([
                "status"  => false,
                "message" => "Something went wrong!",            
                "errorMessage" => $e->getMessage()
            ]); 
        }
    }


    public function check_app_version(){
        $version = request('version');

        $checkVersion = db::table('mobile_utility')
                            ->where('version',$version)
                            ->first();


        if($checkVersion->active == '1'){

            if($checkVersion->maintenance == '1'){

                return response()->json([
                    "status"  => false,                    
                    "message" => 'Sorry! Mobile app is currently on maintenance. Please try again later.'
                ]); 
    
            }else{

                return response()->json([
                    "status"  => true,
                    "message" => 'Mobile app is now live'
                ]); 
            }

        }else{
            return response()->json([
                "status"  => false,                
                "message" => 'Please download the latest version of mobile application.'
            ]); 
        }

    }

    //  get time to check if voucher transaction is now open
    public function get_time(){                         

        $get_current_time = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now());        
        $start_time_of_scan = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->format('Y-m-d 6:00:00'));
        $end_time_of_scan = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->format('Y-m-d 19:00:00'));
                
        return ($get_current_time <= $end_time_of_scan) &&  ($get_current_time >= $start_time_of_scan) ? true  : false ;
        
    }


    // get program items (rice, egg , etc...)
    public function getProgramItems($supplier_id,$reference_num)
    {

        $get_record = db::table('program_items as pi')
                        ->join('supplier_programs as sp', 'pi.item_id', 'sp.item_id')
                        ->where('supplier_id', $supplier_id)
                        ->where('sp.program_id', db::table('voucher')->where('reference_no',$reference_num)->first()->program_id)
                        ->get();

                        
        foreach($get_record as $key => $item){
            if(file_exists(storage_path('/commodities//' .$item->item_profile))){
                $item->base64 = base64_encode(file_get_contents(storage_path('/commodities//' .$item->item_profile)));            
            }else{
                $item->base64 = base64_encode(file_get_contents('public/edcel_images/no-image.jpg'));            
            }
            
            
        }

        return $get_record;
    }



    
    public function search_voucher(){

        try{

            $search_value = request('searchValue');
            $supplier_id      = request('supplierId');
            
            $searched_voucher = db::table('voucher as v')
                                ->select(
                                    'v.reference_no',
                                    'transac_date',
                                    DB::raw("CONCAT(first_name,' ',middle_name,' ',last_name) as fullname"),
                                    'rsbsa_no',
                                    'file_name',
                                    'v.amount_val as current_balance',
                                    'v.amount as default_balance',
                                    'vt.voucher_details_id',   
                                    'shortname as program',
                                    'title as program_title',
                                    DB::raw("YEAR(transac_date) as year_transac"),   
                                    DB::raw("CONCAT(gm.bgy_name,', ',gm.mun_name,', ',gm.prov_name,', ',gm.reg_name) as address"),
                                    'vt.transaction_id'
                                )
                                ->join('voucher_transaction as vt', 'v.reference_no','vt.reference_no')            
                                ->leftJoin('voucher_attachments as va', 'va.transaction_id','vt.transaction_id')
                                ->join('programs as p', 'p.program_id','v.program_id')      
                                ->join('geo_map as gm', 'gm.geo_code','v.geo_code')   
                                ->where('supplier_id',$supplier_id)                    
                                ->orWhere('v.reference_no', 'like', '%' . $search_value . '%')                    
                                ->orWhere('first_name',$search_value )                    
                                ->orWhere('middle_name',$search_value )                    
                                ->orWhere('last_name',$search_value )                                                                     
                                ->groupBy('transaction_id')
                                ->orderBy('transac_date','desc')
                                ->get();

            foreach ($searched_voucher as $key => $item) {
                $get_attachments  = db::table('voucher_attachments')
                                        ->where('transaction_id',$item->transaction_id)                                        
                                        ->get();    
                $get_commodities = db::table('program_items as pi')
                                        ->join('supplier_programs as sp','pi.item_id','sp.item_id')    
                                        ->join('voucher_transaction as vt','vt.sub_program_id','sp.sub_id')
                                        ->where('vt.transaction_id',$item->transaction_id)
                                        ->get();

                $image_array = [];

                foreach($get_attachments as $attachment_key => $attachment_item){
                    if(file_exists('uploads/transactions/attachments'.'/'.$item->program.'/'.$item->year_transac.'/' . $item->rsbsa_no.'/'.$attachment_item->file_name)){
                        array_push($image_array,["name"=>$attachment_item->document,"image"=>base64_encode(file_get_contents('uploads/transactions/attachments'.'/'.$item->program.'/'.$item->year_transac.'/' . $item->rsbsa_no.'/'.$attachment_item->file_name))]);                    
                    }else{
                        
                        array_push($image_array,["name"=>$attachment_item->document,"image"=>base64_encode(file_get_contents('public/edcel_images/no-image.jpg'))]);                    
                    }
                    
                }

                
                $item->base64 = $image_array;

                $item->commodities = $get_commodities;

            }        

            if($searched_voucher){

                return response()->json([
                    "status"  => true,
                    "data" =>$searched_voucher
                ]); 
            }else{
                return response()->json([
                    "status"  => false,
                    "message" => "No data found...",            
                ]); 
            }

        }catch(\Exception $e){

            return response()->json([
                "status"  => false,
                "message" => "Something went wrong!",            
                "errorMessage" => $e->getMessage()
            ]); 
        }
    }


    public function scan_qr_code(){

        
        try{

            $time_limit         = 45; //in minutes
            $reference_number = request('reference_number');
            $supplier_id      = request('user_id');

            // get_programs
            $getPrograms = db::table('program_permissions as pp')                                        
                                    ->where('user_id',$supplier_id)
                                    ->where('status','1')
                                    ->pluck('program_id')->toArray();
            
            // VOUCHER INFO
            $checkReferenceNumber = db::table('voucher as v')
                                    ->join('programs as p','p.program_id','v.program_id')                       
                                    ->where('reference_no', trim($reference_number))->first();
            
            if($checkReferenceNumber){

                // check if user program is same with the scanned voucher
                $checkProgram = in_array($checkReferenceNumber->program_id,$getPrograms);

                if($checkProgram){

                    $checkTime = self::get_time();
                    if($checkTime == true){
                        // echo $checkReferenceNumber->amount_val;
                        
                        if($checkReferenceNumber->voucher_status != 'FULLY CLAIMED' || $checkReferenceNumber->amount_val  > 0.00 ){
                            
                            $check_transaction_time =  db::table('voucher')->select(DB::raw('TIMESTAMPDIFF(MINUTE,scanned_date,NOW()) as minutes_scanned'),DB::raw('TIMESTAMPDIFF(SECOND,scanned_date,NOW()) as seconds_scanned'))
                                                        ->where('reference_no', $reference_number)->first();

                            $get_elapsed_minutes = $time_limit - $check_transaction_time->minutes_scanned ;
                            $get_elapsed_seconds = 300 - $check_transaction_time->seconds_scanned ;
                            $check_if_one_time =  $checkReferenceNumber->one_time_transaction;

                            

                            if(($check_transaction_time->minutes_scanned >= $time_limit )  || (is_null($check_transaction_time->minutes_scanned)) || ($check_transaction_time->minutes_scanned <= $time_limit &&  $checkReferenceNumber->last_scanned_by_id == $supplier_id)){
                                // set already scanned
                                db::table('voucher')->where('reference_no', $reference_number)->update(['is_scanned' => '1','scanned_date' => db::raw('CURRENT_TIMESTAMP'),'last_scanned_by_id' => $supplier_id]);


                                

                                $get_geo_map =  db::table('geo_map')
                                                    ->where('reg_code', $checkReferenceNumber->reg)
                                                    ->where('prov_code',$checkReferenceNumber->prv)
                                                    ->where('mun_code', $checkReferenceNumber->mun)
                                                    ->where('bgy_code',$checkReferenceNumber->brgy)
                                                    ->first();


                                // FERTILIZER CATEGORY
                                $get_unit_measurements   =  db::table('unit_types')->where('status','1')->get();
                                $get_fertilizer_categories   =  db::table('fertilizer_category as fc')
                                                                    ->join('program_items_category as pic','fc.fertilizer_category_id','pic.fertilizer_category_id')
                                                                    ->where('program_id',$checkReferenceNumber->program_id)
                                                                    ->get();
                                                                    
                                $checkReferenceNumber->sub_categories          =  db::table('fertilizer_sub_category')->get();
                                  
                                $clean_fertilizer_categories =  [];
                                $clean_unit_measurements =  [];
                                


                                foreach($get_fertilizer_categories as $item_category){
                                    array_push($clean_fertilizer_categories, 
                                    ["label"=>$item_category->category,"value"=>$item_category->fertilizer_category_id]);
                                }                                


                                foreach($get_unit_measurements as $item_unit_measurement){
                                    array_push($clean_unit_measurements, 
                                    ["label"=>$item_unit_measurement->type,"value"=>$item_unit_measurement->unit_type_id]);
                                }                                



                                $checkReferenceNumber->unit_measurements = $clean_unit_measurements;
                                $checkReferenceNumber->fertilizer_categories = $clean_fertilizer_categories;
                                $checkReferenceNumber->program_items = self::getProgramItems($supplier_id,$reference_number);

                                $minutes_to_miliseconds = $time_limit * 60000;

                                return response()->json([
                                    "status"  => true,                
                                    "voucherInfo" => $checkReferenceNumber,
                                    "timer"  => $minutes_to_miliseconds
                                ]);

                            }else{
                              
                                return response()->json([
                                    "status"  => false,                
                                    "message" => 'This is already scanned. Please try again later.'
                                ]);
                            }

                        }else{

                            return response()->json([
                                "status"  => false,                
                                "message" => 'This voucher is already fully claimed.'
                            ]);
                        }

                    }else{
                        return response()->json([
                            "status"  => false,                
                            "message" => 'You can only transact vouchers from 6:00 am to 7:00 pm only.'
                        ]);
                    }

                }else{

                    return response()->json([
                        "status"  => false,                
                        "message" => 'You are not registered in this program.'
                    ]);
                }

            }else{
                
                return response()->json([
                    "status"  => false,                
                    "message" => 'Invalid reference number.'
                ]);

            }


        }catch(\Exception $e){

            return response()->json([
                "status"  => false,
                "message" => "Something went wrong!",            
                "errorMessage" => $e->getMessage()
            ]); 
        }
  

    }



    // TRANSACT VOUCHER 

    public function transact_voucher(){

        DB::beginTransaction();
        try{
            $supplier_id = request('userId');
            $supplier_name = request('supplierName');
            $voucher_info = request('voucherInfo');
            $cart = request('cart');
            $attachments = request('attachments');
            $transaction_total_amount = request('transactionTotalAmount');
            $longitude = request('longitude');
            $latitude = request('latitude');
            $upload_error_count = 0;

            $voucher_id   = $voucher_info['voucher_id'];
            $reference_no = $voucher_info['reference_no'];
            $fund_id      = $voucher_info['fund_id'];
            
            $program_shortname      =  db::table('programs')->where('program_id',$voucher_info['program_id'])->first()->shortname;

            $transaction_id = Uuid::uuid4();                        

            $voucher_transaction_payload_array = [];
            $voucher_attachment_payload_array = [];

            $checkIfClaimed = db::table('voucher as v')
                                    ->join('programs as p','p.program_id','v.program_id')                       
                                    ->where('reference_no', trim($reference_no))->first();
            

            if($checkIfClaimed->voucher_status != 'FULLY CLAIMED' && $checkIfClaimed->amount_val != 0.00){
            // validate attachments
            foreach($attachments as $item){
             
                $attachment_id = Uuid::uuid4();
            
                
                if($item['name'] == 'Other Documents'){
                    
                    $file_count = count($item['file']);            
                   
                    if($file_count != 0){
                        $other_doc_count = 1 ;
                        foreach($item['file'] as $key => $file){

                            $other_docs_attachment_uuid = Uuid::uuid4();
                            $item_file = $file;
        
                            $item_file      = str_replace('data:image/jpeg;base64,', '', $item_file);
                            $item_file      = str_replace(' ', '+', $item_file);
                            $other_document_name = $voucher_info['rsbsa_no'] . '-'. $reference_no.'-'. $other_doc_count.'-'. $item['name'] . '.jpeg';
        
                            $upload_other_document = Storage::disk('uploads')->put($upload_folder . '/' . $other_document_name, base64_decode($item_file));
        
        
                            $check_file = Storage::disk('uploads')->exists($upload_folder . '/' . $other_document_name);
                            if(!$check_file){
                                $upload_error_count++;
                            }else{    
                                $voucher_attachment_payload = [
                                    "attachment_id" => $other_docs_attachment_uuid,
                                    "voucher_id" => $voucher_id,
                                    "transaction_id" => $transaction_id,
                                    "document" => $item['name'],
                                    "file_name" => $other_document_name
                                ];
                                
                                array_push($voucher_attachment_payload_array,$voucher_attachment_payload);
                            }
                            

                            $other_doc_count++;
                        }

                    }

                } else if($item['name'] == 'Valid ID'){

                    $get_file = $item['file'];

              
                    foreach($item['file'] as $key => $valid_id_file){

                        foreach($valid_id_file as $valid_id_key => $valid_id_file){
                            $valid_id_attachment_id = Uuid::uuid4();
                            $image = $valid_id_file;

                            $image     = str_replace('data:image/jpeg;base64,', '', $image);
                            $image     = str_replace(' ', '+', $image);
                            $imageName = $voucher_info['rsbsa_no'] . '-' . $reference_no.'-'. $item['name'] . '('.strtoupper($valid_id_key).')'. '.jpeg';
                            
                            $upload_folder  = '/attachments'.'/'. $program_shortname.'/'.Carbon::now()->year.'/' . $voucher_info['rsbsa_no'];
                                
                            // UPLOAD FILE
                            $upload_image = Storage::disk('uploads')->put($upload_folder . '/' . $imageName, base64_decode($image));
        
                            $check_file = Storage::disk('uploads')->exists($upload_folder . '/' . $imageName);
                            if(!$check_file){
                                $upload_error_count++;
                            }else{    

                                $voucher_attachment_payload = [
                                    "attachment_id" => $valid_id_attachment_id,
                                    "voucher_id" => $voucher_id,
                                    "transaction_id" => $transaction_id,
                                    "document" => $item['name'],
                                    "file_name" => $imageName
                                ];

                                array_push($voucher_attachment_payload_array,$voucher_attachment_payload);

                            }

                            
                        }

                    }
               
                }else{

                
                    $image = $item['file'];

                    $image     = str_replace('data:image/jpeg;base64,', '', $image);
                    $image     = str_replace(' ', '+', $image);
                    $imageName = $voucher_info['rsbsa_no'] . '-' . $reference_no.'-'. $item['name'] . '.jpeg';
                    
                    $upload_folder  = '/attachments'.'/'. $program_shortname.'/'.Carbon::now()->year.'/' . $voucher_info['rsbsa_no'];
                        
                    // UPLOAD FILE
                    $upload_image = Storage::disk('uploads')->put($upload_folder . '/' . $imageName, base64_decode($image));

                    $check_file = Storage::disk('uploads')->exists($upload_folder . '/' . $imageName);
                    if(!$check_file){
                        $upload_error_count++;
                    }else{    
                        $voucher_attachment_payload = [
                            "attachment_id" => $attachment_id,
                            "voucher_id" => $voucher_id,
                            "transaction_id" => $transaction_id,
                            "document" => $item['name'],
                            "file_name" => $imageName
                        ];
                        array_push($voucher_attachment_payload_array,$voucher_attachment_payload);
                    }
                }
              
            }


            

            if($upload_error_count == 0 ){
                
                
                foreach($cart as $item){
                    $voucher_details_id = Uuid::uuid4();

                    $get_category = db::table('fertilizer_category')->where('fertilizer_category_id',$item['category'])->first()->category;
                    
                    $get_unit_measurement  =  db::table('unit_types')->where('unit_type_id',$item['unitMeasurement'])->where('status','1')->first()->type;

                    $voucher_transaction_payload = [
                        "voucher_details_id" => $voucher_details_id,
                        "transaction_id" => $transaction_id,
                        "reference_no" => $reference_no,
                        "supplier_id" => $supplier_id,
                        "sub_program_id" => $item['sub_id'],
                        "fund_id" => $fund_id,
                        "item_category" => $get_category,
                        "item_sub_category" => $item["subCategory"],
                        "quantity" => $item['quantity'],
                        "total_amount" =>  $item['cashAdded'] > 0 ?  $item['totalAmount']   - $item['cashAdded'] : $item['totalAmount'],
                        "cash_added" => $item['cashAdded'],
                        "latitude" => $latitude,
                        "longitude" => $longitude,
                        "unit_type" => $get_unit_measurement,
                        "transac_by_id" => $supplier_id,
                        "transac_by_fullname" => $supplier_name
                    ];

                    array_push($voucher_transaction_payload_array,$voucher_transaction_payload);
                }


                $insert_voucher_transaction = db::table('voucher_transaction')->insert($voucher_transaction_payload_array);

            
                $insert_voucher_attachments = db::table('voucher_attachments')->insert($voucher_attachment_payload_array);

                
                if($insert_voucher_transaction && $insert_voucher_attachments){


                    $voucher_status = ($checkIfClaimed->amount_val -  $transaction_total_amount) <= 0 ? 'FULLY CLAIMED' : 'PARTIALLY CLAIMED';
                    $compute_remaining_balance = ($checkIfClaimed->amount_val -  $transaction_total_amount) <= 0  ? 0 :  ($checkIfClaimed->amount_val -  $transaction_total_amount);
                    // VOUCHER STATUS TO FULLY CLAMED
                    $update_voucher_status = db::table('voucher')
                                                ->where('reference_no',$reference_no)
                                                ->update(["voucher_status" => $voucher_status , 'amount_val' => $compute_remaining_balance]);

                    if($update_voucher_status){


                        DB::commit();    
                        return response()->json([
                            "status"  => true,
                            "message" => "Successfully submitted!",                        
                            'data' =>$voucher_transaction_payload_array
                        ]); 
    
                    }else{
                        DB::rollBack();
                        return response()->json([
                            "status"  => false,
                            "message" => "Failed to submit!",                        
                        ]); 
                    }

                  
                }else{

                    
                    DB::rollBack();
                    return response()->json([
                        "status"  => false,
                        "message" => "Failed to submit!",                        
                    ]); 
                }
                
            }else{
                
                DB::rollBack();
                return response()->json([
                    "status"  => false,
                    "message" => "Uploading Failed!",                        
                ]); 
            }

        }else{

            return response()->json([
                "status"  => false,
                "message" => "This voucher is already fully claimed!",                        
            ]); 
        }
           


        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                "status"  => false,
                "message" => "Something went wrong!",            
                "errorMessage" => $e->getMessage()
            ]); 
        }
    }



    public function payout_batch_list(){


        try{
            $supplier_id         = request('supplierId');
            $offset              = request('page');
            $selected_filter      = request('selectedFilter');
            
            $payout_batch_list = [];


            if($selected_filter == 'All'){
            $payout_batch_list = db::table('payout_gif_batch as pgb')         
                                    ->leftJoin('payout_gfi_details as pgd','pgd.batch_id','pgb.batch_id')                       
                                    ->where('supplier_id',$supplier_id)                                
                                    ->orderBy('pgb.transac_date','desc')
                                    ->groupBy('pgb.application_number')
                                    ->skip($offset)
                                    ->take(6)                                      
                                    ->get();
            }else if ( $selected_filter == 'Pending'){

                $payout_batch_list = db::table('payout_gif_batch as pgb')         
                                    ->leftJoin('payout_gfi_details as pgd','pgd.batch_id','pgb.batch_id')                       
                                    ->where('supplier_id',$supplier_id)      
                                    ->where('issubmitted','1')                                
                                    ->whereNotNull('application_number')    
                                    ->whereNull('approved_by_approver')   
                                    ->orderBy('pgb.transac_date','desc')
                                    ->groupBy('pgb.application_number')
                                    ->skip($offset)
                                    ->take(6)                                      
                                    ->get();
            }else if ( $selected_filter == 'Approved'){

                $payout_batch_list = db::table('payout_gif_batch as pgb')         
                                    ->leftJoin('payout_gfi_details as pgd','pgd.batch_id','pgb.batch_id')                       
                                    ->where('supplier_id',$supplier_id)      
                                    ->where('issubmitted','1')       
                                    ->whereNotNull('application_number')                              
                                    ->whereNotNull('approved_by_approver')      
                                    ->where('iscomplete','0')   
                                    ->orderBy('pgb.transac_date','desc')
                                    ->groupBy('pgb.application_number')
                                    ->skip($offset)
                                    ->take(6)                                      
                                    ->get();
            }else if ( $selected_filter == 'Paid'){

                $payout_batch_list = db::table('payout_gif_batch as pgb')         
                                    ->leftJoin('payout_gfi_details as pgd','pgd.batch_id','pgb.batch_id')                       
                                    ->where('supplier_id',$supplier_id)      
                                    ->where('issubmitted','1')       
                                    ->whereNotNull('application_number')                              
                                    ->whereNotNull('approved_by_approver') 
                                    ->where('iscomplete','1')       
                                    ->orderBy('pgb.transac_date','desc')
                                    ->groupBy('pgb.application_number')
                                    ->skip($offset)
                                    ->take(6)                                      
                                    ->get();
            }

                                    
            $total_paid_payout = db::select("
                                    select sum(amount) as totalPaidAmount
                                        from (
                                        select amount
                                        from payout_gif_batch as pgb
                                        left Join payout_gfi_details as pgd on pgd.batch_id= pgb.batch_id
                                        where supplier_id = '$supplier_id'  
                                        and  iscomplete = '1'
                                        group By pgd.batch_id
                                        order By pgb.transac_date desc
                                        ) as total_paid_table
                                ");

                                
            $total_pending_payout = db::select("
                                    select sum(amount) as total_pending_payout
                                        from (
                                        select amount
                                        from payout_gif_batch as pgb
                                        left Join payout_gfi_details as pgd on pgd.batch_id= pgb.batch_id
                                        where supplier_id = '$supplier_id'  
                                        and  iscomplete = '0'
                                        group By pgd.batch_id
                                        order By pgb.transac_date desc
                                        ) as total_paid_table
                                    ")[0]->total_pending_payout;
                    
            return response()->json([
                "status"=>true,
                "payout_batch_list" => $payout_batch_list, 
                "total_paid_payout" => isset($total_paid_payout[0]->totalPaidAmount) ? $total_paid_payout[0]->totalPaidAmount : 0, 
                "total_pending_payout" => isset($total_pending_payout) ? $total_pending_payout : 0]
            );
        }catch(\Exception $e){

            return response()->json([
                "status"  => false,
                "message" => "Something went wrong!",            
                "errorMessage" => $e->getMessage()
            ]); 
        }
        
    }
}



