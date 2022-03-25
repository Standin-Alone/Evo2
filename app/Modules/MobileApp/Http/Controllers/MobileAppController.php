<?php

namespace App\Modules\MobileApp\Http\Controllers;

use App\Http\Controllers\Controller;
use File;
use Mail;
use DB;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Support\Arr;
use App\Modules\Login\Models\Login;

class MobileAppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // get sign in for login screen
    public function sign_in()
    {
        //
        $username   = request('username');
        $password   = request('password');
        $random_otp = mt_rand(100000, 999999);
    
        $authenticate = db::table('users')->where('username', $username)->first();
        $get_password = db::table('users')->where('username', $username)->value('password');

        

        $to_email = "";
        $otp_to_send = "";            
        $supplier = '';
        
        if ($authenticate) {
            $get_user_otp_id = 0;
            if(password_verify($password,$get_password)){
          
                    $to_email = $authenticate->email;
     
                    $supplier = db::table('program_permissions as pp')
                              ->select(db::raw("CONCAT(first_name,' ',last_name) as full_name"), 'supplier_id', 'u.user_id','approval_status','gm.reg_code','reg_name','supplier_name')
                              ->join('supplier as s', 's.supplier_id', 'pp.user_id')
                              ->join('users as u', 'u.user_id', 'pp.user_id')       
                              ->join('geo_map as gm', 'gm.reg_code', 'u.reg')                                      
                              ->where('u.user_id', $authenticate->user_id)->first();
              
                
                if(isset($supplier->user_id)){
                     $check_otp = db::table('user_otp')
                                ->where('user_id',$supplier->user_id)
                                ->where('status','1')
                                ->get();
                
                    // check otp if exists
                    if(!$check_otp->isEmpty()){
                        foreach($check_otp as $item){
                            $otp_to_send = $item->otp;
                            $get_user_otp_id = $item->otp_id;
                        }
                    }else{
                        $otp_to_send = $random_otp;
                        // insert otp to user_otp table
                        $get_user_otp_id = db::table('user_otp')->insertGetId([
                            "user_id" => $supplier->user_id,
                            "otp"     => $otp_to_send                    
                        ]);
                    }


                    foreach($check_otp as $item){
                    $current_date_time = Carbon::now(); 
                    $otp_date = Carbon::parse($item->date_created);
                    $check_expiration = $otp_date->diffInDays($current_date_time);
                    
                    if($check_expiration > 0){     
                        db::table('user_otp')
                            ->where('user_id',$supplier->user_id)
                            ->where('otp',$otp_to_send)
                            ->update(["status" => '0']);                                                
                        $otp_to_send = $random_otp;
                        // insert otp to user_otp table
                        $get_user_otp_id = db::table('user_otp')->insertGetId([
                            "user_id" => $supplier->user_id,
                            "otp"     => $otp_to_send                    
                        ]);
                    }

                    }
                    $get_otp_record = db::table('user_otp as uo')
                                            ->select('u.user_id','uo.date_created',db::raw("CONCAT(first_name,' ',last_name) as full_name"),'role')
                                            ->join('users as u','u.user_id','uo.user_id')
                                            ->join('program_permissions as pp','u.user_id','pp.user_id')                                        
                                            ->join('roles as r','r.role_id','pp.role_id')
                                            ->where('uo.otp_id',$get_user_otp_id)
                                            ->where('uo.status',1)
                                            ->first();
                    // get_programs
                    $get_programs = db::table('program_permissions as pp')
                                        ->select('program_id')
                                        ->where('user_id',$supplier->user_id)
                                        ->get();
                                        
                    Mail::send('MobileApp::otp', ["otp_code" => $otp_to_send, "full_name" => $get_otp_record->full_name  , "date" => $get_otp_record->date_created   , "role" => $get_otp_record->role  ], function ($message) use ($to_email, $otp_to_send) {
                        $message->to($to_email)
                                ->subject('OTP');                            
                    });

                    if($supplier->approval_status == '1'){

                        if($authenticate->status == '1'){


                        
                            return json_encode([
                                "Message"     => "true",
                                "OTP"         => $otp_to_send,
                                "email"       => $to_email,
                                "supplier_id" => $supplier->supplier_id,
                                "user_id"     => $supplier->user_id,
                                "full_name"   => $supplier->full_name,
                                "region_code" => $supplier->reg_code,
                                "region_name" => $supplier->reg_name,                                
                                "company_name"=> $supplier->supplier_name,
                                "programs"    => $get_programs
                            ]);
                        }else{
                            return json_encode(["Message" => "Disabled"]);
                        }
               
                    }else{

                        return json_encode(["Message" => "Your account status is for approval."]);
                    }

                }else{
                    return json_encode(["Message" => "no account"]);
                }
               
            }else{
                return json_encode(["Message" => "false"]);
            }
        } else {
            return json_encode(["Message" => "no account"]);
        }
    }


     /**
     * Action: when user click "Send Reset Password Link"
     */
    public function send_btn_link_req_form(Request $request){
        $this->loginModel = new Login;

        // check email if exists
        $check_email = $this->loginModel->check_email($request->email);

        // get user
        $users = $this->loginModel->get_user($request->email);

        $role_sets = [];
        
         // check if the user or user email exists
        if($check_email == true){
            foreach($users as $user_roles){
                $role = $user_roles->role;
                $role_sets[] = $role;
            }
            foreach($users as $user){
                if($request->email == $user->email){
                    // create token
                    // $token = sha1(rand(1, 30));
                    $uuid = $user->user_id;
                    $email = $user->email;
                    $username = $user->username;
                    $firstname = $user->first_name;
                    $lastname = $user->last_name;
                    $extname = $user->ext_name;
                    $reset_status = $user->password_reset_status;
                    $date_created = Carbon::now('GMT+8')->toDateTimeString();

                    // Change password_reset_status to "1"
                    $this->loginModel->reset_status_active($uuid, $date_created);

                    // send reset link to email
                    $this->loginModel->email_reset_link($uuid, $email, $username, $firstname, $lastname, $extname, $role_sets, $date_created);
                    $success_response = ['success'=> true, 'message' => 'The reset password link  have been send to your email: "'.$email.'".', 'auth' => false];
                    return response()->json($success_response, 200);
                }
                else{
                    $error_response = ['error'=> true, 'message'=>'The input email is incorrect', 'auth'=>false];
                    return response()->json($error_response, 302);
                }
            }
        }
        else{
            $error_response = ['error'=> true, 'message'=>"The email doesn't exists!", 'auth'=>false];
            return response()->json($error_response, 302);
        }
    }


      // resend OTP
      public function resendOTP()
      {
  
        $email      = request('email');
        $user_id      = request('user_id');
        $random_otp = mt_rand(100000, 999999);
        
        // disable the previous otp
        db::table('user_otp')
            ->where('user_id',$user_id)
            ->update([
            "status" => '0'
        ]);

        // insert new otp
       $get_user_otp_id =  db::table('user_otp')->insertGetId([
            "user_id" => $user_id,
            "otp"     => $random_otp                    
        ]);

        $get_otp_record = db::table('user_otp as uo')
                                        ->select('u.user_id','uo.date_created','username','role')
                                        ->join('users as u','u.user_id','uo.user_id')
                                        ->join('program_permissions as pp','u.user_id','pp.user_id')                                        
                                        ->join('roles as r','r.role_id','pp.role_id')
                                        ->where('uo.otp_id',$get_user_otp_id)
                                        ->where('uo.status','1')
                                        ->first();
                
        Mail::send('MobileApp::otp', ["otp_code" => $random_otp, "full_name" => $get_otp_record->username  , "date" => $get_otp_record->date_created   , "role" => $get_otp_record->role  ], function ($message) use ($email, $random_otp) {
                    $message->to($email)
                    ->subject('OTP');
                            
                });
         
  
          return json_encode(["Message" => 'true',"OTP"     => $random_otp]);
      }
  
      // validate otp
      public function validateOTP(){
          
          $code = request('code');
          $user_id = request('user_id');
  
          $validate_otp =  db::table('user_otp')
                              ->where('user_id',$user_id)
                              ->where('otp',$code)
                              ->limit(1)                            
                              ->get();
          
          if(!$validate_otp->isEmpty()){
            $current_date_time = Carbon::now(); 
            $otp_date = Carbon::parse($validate_otp[0]->date_created);
            $check_expiration = $otp_date->diffInDays($current_date_time);
         
            if($check_expiration == 0){

              db::table('user_otp')
                  ->where('user_id',$user_id)
                  ->where('otp',$code)
                  ->update(["status" => '0']);                                
              return 'true';

            }else{
                return 'expired';
            }
            
          }else{
              return 'false';
          }
  
      }

    
    //   discard transaction
    public function discard_transaction(){
        $reference_num = request('reference_num');
        // set already scanned to 0
        db::table('voucher')->where('reference_no', $reference_num)->update(['is_scanned' => '0']);
    }
    // get scanned vouchers for home screen
    public function get_scanned_vouchers($supplier_id,$offset)
    {
        


        $get_scanned_vouchers = db::table('voucher as v')
            ->select(
                'v.reference_no',
                'transac_date',
                DB::raw("CONCAT(first_name,' ',middle_name,' ',last_name) as fullname"),
                'rsbsa_no',
                'file_name',
                'v.amount_val as current_balance',
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
            // ->where('document', 'Farmer with Commodity')            
            ->groupBy('v.reference_no')
            ->orderBy('transac_date', 'DESC')     
            ->skip($offset)
            ->take(2)               
            ->get();
        

            $total_vouchers = db::table('voucher as v')
                                    ->select(
                                        db::raw('COUNT(distinct vt.transaction_id) as total_vouchers')
                                    )
                                    ->join('voucher_transaction as vt', 'v.reference_no','vt.reference_no')            
                                    ->leftJoin('voucher_attachments as va', 'va.transaction_id','vt.transaction_id')
                                    ->join('programs as p', 'p.program_id','v.program_id')      
                                    ->join('geo_map as gm', 'gm.geo_code','v.geo_code')           
                                    ->where('supplier_id', $supplier_id)              
                                    ->groupBy('v.reference_no')                                    
                                    ->first()->total_vouchers;

            
            foreach ($get_scanned_vouchers as $key => $item) {
                $get_attachments  = db::table('voucher_attachments')
                                        ->where('transaction_id',$item->transaction_id)                                        
                                        ->get();    

                $image_array = [];

                foreach($get_attachments as $attachment_key => $attachment_item){
                    array_push($image_array,base64_encode(file_get_contents('uploads/transactions/attachments'.'/'.$item->program.'/'.$item->year_transac.'/' . $item->rsbsa_no.'/'.$attachment_item->file_name)));                    
                }

                
                $item->base64 = $image_array;

            }                        
        
        return json_encode(["scanned_vouchers"=>$get_scanned_vouchers,"total_vouchers"=>$total_vouchers]);
    }


    // get scanned vouchers TODAY for home screen
    public function get_scanned_vouchers_today($supplier_id,$offset)
    {
        


        $get_scanned_vouchers = db::table('voucher as v')
            ->select(
                'v.reference_no',
                'transac_date',
                DB::raw("CONCAT(first_name,' ',last_name) as fullname"),
                'rsbsa_no',
                'file_name',
                'v.amount_val',
                'vt.voucher_details_id',   
                'shortname as program',
                 DB::raw("YEAR(transac_date) as year_transac"),
                   
              )
            ->join('voucher_transaction as vt', 'v.reference_no','vt.reference_no')            
            ->leftJoin('voucher_attachments as va', 'va.voucher_details_id','vt.voucher_details_id')
            ->join('programs as p', 'p.program_id','v.program_id')                   
            ->where('supplier_id', $supplier_id)  
            ->where('transac_date', DB::raw('NOW()'))  
            
            // ->where('document', 'Farmer with Commodity')            
            ->groupBy('v.reference_no')
            ->orderBy('transac_date', 'DESC')     
            ->skip($offset == 1 ? 0 : $offset)
            ->take(2)               
            ->get();

      
            foreach ($get_scanned_vouchers as $key => $item) {
                
                $item->base64 = base64_encode(file_get_contents('uploads/transactions/attachments'.'/'.$item->program.'/'.$item->year_transac.'/' . $item->rsbsa_no.'/'.$item->file_name));
                
            }                        


        return json_encode($get_scanned_vouchers);
    }

    public function get_transactions_history($reference_num){
        $get_voucher_transactions = db::table('voucher_transaction as vt')                                    
                                    ->join('supplier_programs as sp', 'sp.sub_id', 'vt.sub_program_id')
                                    ->join('program_items as pi','pi.item_id','sp.item_id')                                            
                                    ->where('reference_no', $reference_num)->get();
        
        return $get_voucher_transactions; 
    }

    
    
       
    //  get time to check if voucher transaction is now open
    public function get_time(){                         

        $get_current_time = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now());        
        $start_time_of_scan = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->format('Y-m-d 6:00:00'));
        $end_time_of_scan = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->format('Y-m-d 18:00:00'));
                
        return ($get_current_time <= $end_time_of_scan) &&  ($get_current_time >= $start_time_of_scan) ? 'true'  : 'false' ;
        
    }



    // get voucher info for farmer profile screen (QRCODE SCREEN)
    public function get_voucher_info()
    {
        //
        $time_limit         = 5; //in minutes
        $reference_num      = request('reference_num');
        $supplier_id        = request('supplier_id');
        $programs           = request('programs');
        
        $get_info           = db::table('voucher as v')
                                ->join('programs as p','p.program_id','v.program_id')                       
                                ->where('reference_no', $reference_num)->get();

        // get_programs
        $get_programs = db::table('program_permissions as pp')                                        
                                        ->where('user_id',$supplier_id)
                                        ->where('status','1')
                                        ->pluck('program_id');
        $program_array = [];
        foreach($get_programs as $item){

            array_push($program_array,$item);
        }

        // get voucher program id 
        $voucher_program_id = db::table('voucher as v')                                          
                       ->where('reference_no', $reference_num)->first();
        
        if(isset($voucher_program_id->program_id)){
        // check if voucher program  is equal to the array of merchant programs
        if(in_array($voucher_program_id->program_id,$program_array)){


            //  check if voucher transaction is now open
            if($this->get_time() == 'true'){

                $get_last_scanned_by_id = db::table('voucher')->where('reference_no',$reference_num)->first()->last_scanned_by_id;
                if (!$get_info->isEmpty()) {
                
                

                    $check_transaction_time =  db::table('voucher')->select(DB::raw('TIMESTAMPDIFF(MINUTE,scanned_date,NOW()) as minutes_scanned'),DB::raw('TIMESTAMPDIFF(SECOND,scanned_date,NOW()) as seconds_scanned'))
                                                    ->where('reference_no', $reference_num)->first();


                        
                        $get_voucher = db::table('voucher')->where('reference_no', $reference_num)->first();
                        $get_elapsed_minutes = $time_limit - $check_transaction_time->minutes_scanned ;
                        $get_elapsed_seconds = 300 - $check_transaction_time->seconds_scanned ;
                        $check_if_one_time =  $get_info[0]->one_time_transaction;

                        // check transaction time to access voucher 
                        // condition for same supplier || ($check_transaction_time->minutes_scanned <= $time_limit && $get_last_scanned_by_id == $supplier_id)
                        if(($check_transaction_time->minutes_scanned >= $time_limit )  || (is_null($check_transaction_time->minutes_scanned))){

                            // set already scanned
                            db::table('voucher')->where('reference_no', $reference_num)->update(['is_scanned' => '1','scanned_date' => db::raw('CURRENT_TIMESTAMP'),'last_scanned_by_id' => $supplier_id]);
                            
                            $get_region       = $get_voucher->reg;
                            $get_province     = $get_voucher->prv;
                            $get_municipality = $get_voucher->mun;
                            $get_brgy         = $get_voucher->brgy;
                
                
                            $get_geo_map =  db::table('geo_map')
                                        ->where('reg_code', $get_region)
                                        ->where('prov_code', $get_province)
                                        ->where('mun_code', $get_municipality)
                                        ->where('bgy_code', $get_brgy)
                                        ->first();
                
                            $check_balance = $get_voucher->amount_val;
                
                            foreach($get_info as $item){          
                                $item->Available_Balance = $check_balance;
                                $item->Region            = $get_geo_map->reg_name;
                                $item->Province          = $get_geo_map->prov_name;
                                $item->Municipality      = $get_geo_map->mun_name;
                                $item->Barangay          = $get_geo_map->bgy_name;
                            }
                            
                            $get_program_items   = $this->getProgramItems($supplier_id,$reference_num);
                            $get_recent_claiming = $this->get_transactions_history($reference_num);
                
                            // validate voucher
                            if($get_info[0]->voucher_status == 'FULLY CLAIMED' || $get_info[0]->Available_Balance == 0.00){
                                db::table('voucher')->where('reference_no', $reference_num)->update(['is_scanned' => '0']);
                            }   

                            $minutes_to_miliseconds = $time_limit * 60000;

                            return json_encode(["Message"           => 'true',
                                                    "data"          => $get_info, 
                                                    "program_items" => $get_program_items,
                                                    "history"       => $get_recent_claiming,
                                                    "time_limit"   => $minutes_to_miliseconds
                                                ]);
                
                        
                        }else{
                          
                            return json_encode(["Message" => 'on-going process',"Minutes" => $get_elapsed_minutes,"Seconds" => $get_elapsed_seconds ]);                        
                            // return json_encode(["Message" => 'already scanned']);
                        }

                  
            

                }else{
                    db::table('voucher')->where('reference_no', $reference_num)->update(['is_scanned' => '0']);
                    return json_encode(["Message" => 'false']);            
                }
                    
            }else{

                return json_encode(["Message" => 'Not Yet Open']);    
            }

            }else{
                return json_encode(["Message" => 'invalid program.']);
                        
            }
        }else{
            return json_encode(["Message" => 'false']);
        }
    }







    // insert attachment function
    public function insertAttachment($item,$uuid,$voucher_info,$program){

        // count error upload 
        $upload_error_count = 0;
        // insert attachment data to database
        $attachment_uuid        = Uuid::uuid4();
        $front_attachment_uuid  = Uuid::uuid4();
        $back_attachment_uuid   = Uuid::uuid4();
        $attachment_info = [];


        $base_path = './uploads/transactions';
        
        if ($item->name == 'Other Documents') {

            $file_count = count($item->file);            
            if($file_count != 0){

                $upload_folder  = '/attachments'.'/'. $program.'/'.Carbon::now()->year.'/' . $voucher_info->rsbsa_no;
              

                foreach($item->file as $item_value ){

                    $other_docs_attachment_uuid        = Uuid::uuid4();
                    $item_file = $item_value;

                    $item_file      = str_replace('data:image/jpeg;base64,', '', $item_file);
                    $item_file      = str_replace(' ', '+', $item_file);
                    $other_document_name = $voucher_info->rsbsa_no . '-'. $other_docs_attachment_uuid.'-' . $item->name . '.jpeg';

                    $upload_other_document = Storage::disk('uploads')->put($upload_folder . '/' . $other_document_name, base64_decode($item_file));


                    $check_file = Storage::disk('uploads')->exists($upload_folder . '/' . $other_document_name);

                    // count error if upload fail
                    if(!$check_file){
                        $upload_error_count++;
                    }else{    
               

                        
                        $other_document_info = [
                            'attachment_id'      => $other_docs_attachment_uuid,                  
                            'voucher_details_id' => $uuid,                  
                            'document'           => $item->name,
                            'file_name'          => $other_document_name,
           
                        ];


                        // $attachment_info[] = $other_document_info;
                        array_push($attachment_info,$other_document_info);

                    }
                }

            }


        } 

        else if ($item->name == 'Valid ID') {
            $id_front = $item->file[0]->front;
            $id_back  = $item->file[0]->back;

            // front page of id 
            $id_front      = str_replace('data:image/jpeg;base64,', '', $id_front);
            $id_front      = str_replace(' ', '+', $id_front);
            $id_front_name = $voucher_info->rsbsa_no . '-'. $front_attachment_uuid.'-' . $item->name . '(front)'. '.jpeg';

            // back page of id
            $id_back      = str_replace('data:image/jpeg;base64,', '', $id_back);
            $id_back      = str_replace(' ', '+', $id_back);
            $id_back_name = $voucher_info->rsbsa_no . '-' . $back_attachment_uuid.'-' . $item->name . '(back)' . '.jpeg';

            
            $upload_folder  = '/attachments'.'/'. $program.'/'.Carbon::now()->year.'/' . $voucher_info->rsbsa_no;
            
            
            

            // Check Folder if exist for farmers attachment;
            // if (!File::isDirectory($base_path)) {     

            //     File::makeDirectory($base_path.$upload_folder,0755,true);                                                
            // }
            
            
               
            if (File::isDirectory($base_path)) {                                
                $program_path = $base_path.'/attachments'.'/'.$program;
                if(!File::isDirectory($program_path)){

                    File::makeDirectory($program_path, 0775, true);                                
                    $by_year_path = $program_path. '/'.Carbon::now()->year;
                    
                    if(!File::isDirectory($by_year_path)){
                        
                        File::makeDirectory($by_year_path, 0775, true);                                
                        $rsbsa_path = $by_year_path.'/'. $voucher_info->rsbsa_no;

                        if(!File::isDirectory($rsbsa_path)){
                            File::makeDirectory($rsbsa_path, 0775, true); 
                        }
                    }
                }                
            } 

            
            $upload_front_id = Storage::disk('uploads')->put($upload_folder . '/' . $id_front_name, base64_decode($id_front));
            $upload_back_id = Storage::disk('uploads')->put($upload_folder . '/' . $id_back_name, base64_decode($id_back));

            
            $check_file_front_id =  Storage::disk('uploads')->exists($upload_folder . '/' . $id_front_name) ;
            $check_file_back_id  = Storage::disk('uploads')->exists($upload_folder . '/' . $id_back_name);

            // count error if upload fail
            if(!($check_file_front_id && $check_file_back_id)){
                $upload_error_count++;
            }else{                
                 $attachment_info[] = [
                    [
                        'attachment_id'      => $front_attachment_uuid,                  
                        'voucher_details_id' => $uuid,                  
                        'document'           => $item->name,
                        'file_name'          => $id_front_name,
                    ]
                    ,
                    [
                        'attachment_id'      => $back_attachment_uuid,                  
                        'voucher_details_id' => $uuid,                  
                        'document'           => $item->name,
                        'file_name'          => $id_back_name,
                    ]];
            }

        }
      else {

            $image = $item->file;

            $image     = str_replace('data:image/jpeg;base64,', '', $image);
            $image     = str_replace(' ', '+', $image);
            $imageName = $voucher_info->rsbsa_no . '-' . $back_attachment_uuid.'-'. $item->name . '.jpeg';
            
            $upload_folder  = '/attachments'.'/'. $program.'/'.Carbon::now()->year.'/' . $voucher_info->rsbsa_no;
                        
            
            // Check Folder if exist for farmers attachment;
            
            
            if (File::isDirectory($base_path)) {                                
                $program_path = $base_path.'/attachments'.'/'.$program;
                if(!File::isDirectory($program_path)){

                    File::makeDirectory($program_path, 0775, true);                                
                    $by_year_path = $program_path. '/'.Carbon::now()->year;
                    
                    if(!File::isDirectory($by_year_path)){
                        
                        File::makeDirectory($by_year_path, 0775, true);                                
                        $rsbsa_path = $by_year_path.'/'. $voucher_info->rsbsa_no;

                        if(!File::isDirectory($rsbsa_path)){
                            File::makeDirectory($rsbsa_path, 0775, true); 
                        }
                    }
                }                
            } 

            $upload_image = Storage::disk('uploads')->put($upload_folder . '/' . $imageName, base64_decode($image));

            $check_file_back_id  = Storage::disk('uploads')->exists($upload_folder . '/' . $imageName);

            // count error if upload fail
            if(!$check_file_back_id){
                $upload_error_count++;
            }else{    
               

               

                $attachment_info[] = [
                    'attachment_id'      => $attachment_uuid,                  
                    'voucher_details_id' => $uuid,                  
                    'document'           => $item->name,
                    'file_name'          => $imageName,
   
                ];
            }
        }

        return [ 'upload_error_count' => $upload_error_count, 'attachment_info'    =>   $attachment_info ];
    }

    

    //SUBMIT FUNCTION OF CLAIM VOUCHER RRP
    // public function submit_voucher_rrp()
    // {

    //     try {

            
    //         $uuid         = Uuid::uuid4();
    //         $voucher_info = json_decode(request('voucher_info'));
    //         $commodity    = json_decode(request('commodity'));
    //         $attachments  = json_decode(request('attachments'));
    //         $attachment_response = '';
    //         $attachment_info = [];
    //         $attachment_error_count = 0;
           

    //         // upload attachments to file server 
    //         foreach ($attachments as $item) {
    //             $attachment_response = $this->insertAttachment($item,$uuid,$voucher_info,$voucher_info->program);
    //             $attachment_info[] = $attachment_response['attachment_info'];                
    //             $attachment_error_count += $attachment_response['upload_error_count'];
    //         }

    //         // check if uploading is successfull
    //         if($attachment_error_count == 0){
    //             // insert to voucher transaction table
    //             db::table('voucher_transaction')->insert(
    //                 [
    //                     'voucher_details_id'  => $uuid,
    //                     'reference_no'        => $voucher_info->reference_no,
    //                     'supplier_id'         => $voucher_info->supplier_id,
    //                     'sub_program_id'      => $commodity->sub_id,
    //                     'fund_id'             =>  $voucher_info->fund_id,
    //                     'quantity'            =>  $commodity->quantity,
    //                     'amount'              =>  $commodity->fertilizer_amount,
    //                     'total_amount'        =>  $commodity->total_amount,
    //                     'latitude'            =>  $voucher_info->latitude,
    //                     'longitude'           =>  $voucher_info->longitude,
    //                     'transac_by_id'       =>  $voucher_info->supplier_id,
    //                     'transac_by_fullname' =>  $voucher_info->full_name,
    //                 ]
    //             );
                    
                
    //             // get the attachments to batch insert to database
    //             foreach($attachment_info as $item){
                    
    //                 if(count($item[0]) >= 1){
    //                     foreach($item[0] as $value){
    //                         $encode_valid_id =  json_encode($value);
    //                         $decode_valid_id = json_decode($encode_valid_id);
    //                         // insert pictures in database
    //                         db::table('voucher_attachments')->insert([
    //                             'attachment_id'      => $decode_valid_id->attachment_id,
    //                             'voucher_details_id' => $decode_valid_id->voucher_details_id,
    //                             'document'           => $decode_valid_id->document,
    //                             'file_name'          => $decode_valid_id->file_name,
    //                         ]);
    //                     }
    //                 }else{
    //                         $encode_attachment =  json_encode($item[0]);
    //                         $decode_attachment = json_decode($encode_attachment);
    //                         db::table('voucher_attachments')->insert([
    //                             'attachment_id'      => $decode_attachment->attachment_id,
    //                             'voucher_details_id' => $decode_attachment->voucher_details_id,
    //                             'document'           => $decode_attachment->document,
    //                             'file_name'          => $decode_attachment->file_name,
    //                         ]);
    //                 }
                                        
    //             }
                
    //             //  compute remaining balance
    //             $compute_remaining_bal = $voucher_info->current_balance - $commodity->total_amount;

    //             // update  voucher gen table amount_val                
    //             db::table('voucher')
    //                 ->where('reference_no', $voucher_info->reference_no)
    //                 ->update([
    //                     'amount_val'     => $compute_remaining_bal, 
    //                     'voucher_status' => 'FULLY CLAIMED',                    
    //                 ]);


    //         // set already scanned to 0
    //         db::table('voucher')->where('reference_no', $voucher_info->reference_no)->update(['is_scanned' => '0']);
                   
         
    //             return 'success';
    //         }else{

    //             return 'error';
    //         }
           
    //     } catch (\Exception $e) {
    //         echo json_encode(array(["Message"    => $e->getMessage(), 
    //                                 "StatusCode" => $e->getCode()]));
    //     }
    // }

    //SUBMIT FUNCTION OF voucher transaction
    public function submit_voucher() 
    {

        try {
         
            $voucher_info = json_decode(request('voucher_info'));
            $commodity    = json_decode(request('commodity'));
            $attachments  = json_decode(request('attachments'));
            $attachment_response = '';
            $attachment_info = [];
            $attachment_error_count = 0;
            $transaction_id =  Uuid::uuid4();
            $get_voucher_status = db::table('voucher')
                                                ->where('reference_no',$voucher_info->reference_no)
                                                ->first();

                                        
                                                
                                                
          
            

            if($get_voucher_status->voucher_status != 'FULLY CLAIMED'  && $get_voucher_status->amount_val != 0.00) {
            // upload attachments to file server 
            foreach ($attachments as $item) {
                $attachment_response = $this->insertAttachment($item,0,$voucher_info,$voucher_info->program);
                $attachment_info[] = $attachment_response['attachment_info'];                
                $attachment_error_count += $attachment_response['upload_error_count'];
            }



            // check if uploading is successfull
            if($attachment_error_count == 0){
                // insert to voucher transaction table
                $voucher_details_uuid = '';
                $sum_total_amount = 0;

               

                $attachment_insert_error_count = 0;
               
                // get the attachments to batch insert to database
                foreach($attachment_info as $key => $item){
                   
                    if( isset($item[0])){

                        if(count($item[0]) == 2){
                        
                            foreach($item[0] as $value){
                                
                                $encode_valid_id =  json_encode($value);                                                    
                                $decode_valid_id =  json_decode($encode_valid_id);
                                // insert pictures in database
                                $insert_valid_id = db::table('voucher_attachments')->insert([
                                    'attachment_id'      => $decode_valid_id->attachment_id,
                                    'voucher_id'         => $voucher_info->voucher_id,
                                    'transaction_id'     => $transaction_id,
                                    'document'           => $decode_valid_id->document,
                                    'file_name'          => $decode_valid_id->file_name,
                                ]);


                                if(!$insert_valid_id){
                                    $attachment_insert_error_count++;
                                }

                                
                            }
                        }else if($key == 3){
                            foreach($item as $other_documents_item){
                                $encode_item =  json_encode($other_documents_item);                                                    
                                $decode_item =  json_decode($encode_item);
                                $insert_other_documents = db::table('voucher_attachments')->insert([
                                    'attachment_id'      => $decode_item->attachment_id,
                                    'voucher_id'         => $voucher_info->voucher_id,
                                    'transaction_id'     => $transaction_id,
                                    'document'           => $decode_item->document,
                                    'file_name'          => $decode_item->file_name,
                                ]);
                                
                                if(!$insert_other_documents){
                                    $attachment_insert_error_count++;
                                }
                            }
                        }else{
                                $encode_attachment =  json_encode($item[0]);
                                $decode_attachment = json_decode($encode_attachment);
                                $insert_receipt_and_farmer_with_commodity = db::table('voucher_attachments')->insert([
                                    'attachment_id'      => $decode_attachment->attachment_id,
                                    'voucher_id'         => $voucher_info->voucher_id,
                                    'transaction_id'     => $transaction_id,
                                    'document'           => $decode_attachment->document,
                                    'file_name'          => $decode_attachment->file_name,
                                ]);
    
                                if(!$insert_receipt_and_farmer_with_commodity){
                                    $attachment_insert_error_count++;
                                }
                        }

                    }else if($key == 3){
                        foreach($item as $other_documents_item){
                            $encode_item =  json_encode($other_documents_item);                                                    
                            $decode_item =  json_decode($encode_item);
                            $insert_other_documents = db::table('voucher_attachments')->insert([
                                'attachment_id'      => $decode_item->attachment_id,
                                'voucher_id'         => $voucher_info->voucher_id,
                                'transaction_id'     => $transaction_id,
                                'document'           => $decode_item->document,
                                'file_name'          => $decode_item->file_name,
                            ]);
                            
                            if(!$insert_other_documents){
                                $attachment_insert_error_count++;
                            }
                        }
                    }else{
                            $encode_attachment =  json_encode($item[0]);
                            $decode_attachment = json_decode($encode_attachment);
                            $insert_receipt_and_farmer_with_commodity = db::table('voucher_attachments')->insert([
                                'attachment_id'      => $decode_attachment->attachment_id,
                                'voucher_id'         => $voucher_info->voucher_id,
                                'transaction_id'     => $transaction_id,
                                'document'           => $decode_attachment->document,
                                'file_name'          => $decode_attachment->file_name,
                            ]);

                            if(!$insert_receipt_and_farmer_with_commodity){
                                $attachment_insert_error_count++;
                            }
                    }
                                        
                }   


                // insert transaction if attachments uploaded
                if($attachment_insert_error_count == 0){
                    foreach($commodity as $item){
                        $voucher_details_uuid = Uuid::uuid4();
                        db::table('voucher_transaction')->insert(
                            [
                                'voucher_details_id'  => $voucher_details_uuid,
                                'transaction_id'     => $transaction_id,
                                'reference_no'        => $voucher_info->reference_no,
                                'supplier_id'         => $voucher_info->supplier_id,
                                'sub_program_id'      => $item->sub_id,
                                'fund_id'             =>  $voucher_info->fund_id,
                                'quantity'            =>  $item->quantity,
                                'amount'              =>  $item->price,
                                'item_category'        =>  $item->item_category,
                                'total_amount'        =>  $item->total_amount,
                                'latitude'            =>  $voucher_info->latitude,
                                'longitude'           =>  $voucher_info->longitude,
                                'transac_by_id'       =>  $voucher_info->supplier_id,
                                'transac_by_fullname' =>  $voucher_info->full_name,
                            ]
                        );
        
                        // compute total amount
                        $sum_total_amount += $item->total_amount;        
                    }

                    //  compute remaining balance
                    $compute_remaining_bal = $voucher_info->current_balance - $sum_total_amount;

                    db::table('voucher_transaction_draft')
                                ->where('reference_no',$voucher_info->reference_no)
                                ->delete();
                    
                    // update  voucher gen table amount_val
                    db::table('voucher')
                        ->where('reference_no', $voucher_info->reference_no)
                        ->update([
                            'amount_val'     => $compute_remaining_bal <= 0 ? 0.00 : $compute_remaining_bal, 
                            'voucher_status' =>  $compute_remaining_bal <= 0 ? 'FULLY CLAIMED'  : 'PARTIALLY CLAIMED' ,
                        ]);

                    // set already scanned to 0
                    db::table('voucher')->where('reference_no', $voucher_info->reference_no)->update(['is_scanned' => '0']);
                        
                    return 'success';
                }else{

                    return 'error';

                }               

            
            }else{
                return 'error';
            }
            }else{

                return 'claimed';
            }
        } catch (\Exception $e) {
            echo json_encode(array(["Message"    => $e->getMessage(), 
                                    "StatusCode" => $e->getCode()]));
        }
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



    //delete saved items to cart draft
    public function delete_cart(){
        $cart   = request('cart');
        $result = '';
        $count_success = 0;
        foreach($cart as $value){

            $reference_no   = $value['reference_no'];
            $supplier_id   = $value['supplier_id'];
            $item_category   = $value['item_category'];
            $sub_program_id   = $value['sub_id'];
            
            // delete item record to voucher transaction draft if remove
            $item_delete = db::table('voucher_transaction_draft')                                
                                ->where('reference_no',$reference_no)
                                ->where('supplier_id',$supplier_id)
                                ->where('sub_program_id',$sub_program_id)
                                ->delete();

            if($item_delete){
                $count_success++;
            }
        }

        
        if($count_success == count($cart)){

            $result = json_encode(["message" => 'true']);
        }else{
            $result = json_encode(["message" => 'false']);
        }

        
        return $result;

    }


    // checkout update cart
    public function checkout_update_cart(){        
        $cart   = request('cart');
        $count_success = 0;
        $result = '' ;  
        foreach($cart as $value){

            $reference_no   = $value['reference_no'];
            $supplier_id   = $value['supplier_id'];
            $item_category   = $value['item_category'];
            $sub_program_id   = $value['sub_id'];
            $quantity   = $value['quantity'];
            $amount   = $value['price'];
            $total_amount   = $value['total_amount'];
            


            $check_if_draft_exist = db::table('voucher_transaction_draft')
                                    ->where('reference_no',$reference_no)
                                    ->where('supplier_id',$supplier_id)
                                    ->where('sub_program_id',$sub_program_id)
                                    ->where('item_category',$item_category != '' ? $item_category : '')
                                    ->get();

            if(!$check_if_draft_exist->isEmpty()){


         
            $update_voucher_transaction_draft = db::table('voucher_transaction_draft')
                                        ->where('reference_no',$reference_no)
                                        ->where('supplier_id',$supplier_id)
                                        ->where('sub_program_id',$sub_program_id)
                                        ->where('item_category',$item_category != '' ? $item_category : '')
                                        ->update([
                                            
                                            'reference_no' => $reference_no,
                                            'supplier_id' => $supplier_id,
                                            'item_category' => $item_category,
                                            'sub_program_id' => $sub_program_id,
                                            'quantity' => $quantity,
                                            'amount' => $amount,
                                            'total_amount' => $total_amount
                                        ]);

                                          
                if($update_voucher_transaction_draft){
                    $count_success++;                    
                }else{
                    $count_success = count($cart);    
                }

            }else{
                $count_success = count($cart);
              
            }
        
        }

      
      

        $result = json_encode(["message" => 'true']);
       

        
        return $result;

    }    



    // check if voucher has draft transaction
    public function check_draft_transaction(){
        $supplier_id = request('supplier_id');
        $reference_no = request('reference_no');

        $result = '';
        $check_draft_transaction = db::table('voucher_transaction_draft')
                                        ->where('reference_no',$reference_no)
                                        ->where('supplier_id',$supplier_id)
                                        ->get();
                                        
        if(count($check_draft_transaction) > 0){
            $result = json_encode(["message"=>'true','draft_cart'=>$check_draft_transaction]);
        }else{

            $result = json_encode(["message"=>'false']);
        }                                     

        return $result;

    }    

    // save items to cart as draft
    public function save_added_to_cart(){

        
        $cart   = request('cart');
        $count_success = 0;
        $result = '' ;  
        db::table('voucher_transaction_draft')
                    ->where('reference_no',$cart[0]['reference_no'])
                    ->delete();
        foreach($cart as $value){


            $voucher_transaction_draft_id =  Uuid::uuid4();
            $reference_no   = $value['reference_no'];
            $supplier_id   = $value['supplier_id'];
            $item_category   = $value['item_category'];
            $sub_program_id   = $value['sub_id'];
            $quantity   = $value['quantity'];
            $amount   = $value['price'];
            $total_amount   = $value['total_amount'];
            


            $check_if_draft_exist = db::table('voucher_transaction_draft')
                                    ->where('reference_no',$reference_no)
                                    ->where('supplier_id',$supplier_id)
                                    ->where('sub_program_id',$sub_program_id)
                                    ->get();

            if($check_if_draft_exist->isEmpty()){


                

        
            $insert_voucher_transaction = db::table('voucher_transaction_draft')
                                        ->insert([
                                            'voucher_transaction_draft_id' => $voucher_transaction_draft_id,
                                            'reference_no'                 => $reference_no,
                                            'supplier_id'                  => $supplier_id,
                                            'item_category'                => $item_category,
                                            'sub_program_id'               => $sub_program_id,
                                            'quantity'                     => $quantity,
                                            'amount'                       => $amount,
                                            'total_amount'                 => $total_amount
                                        ]);

                                        
                if($insert_voucher_transaction){
                    $count_success++;
                }

            }else{


                $check_if_category_exist = db::table('voucher_transaction_draft')
                                            ->where('reference_no',$reference_no)
                                            ->where('supplier_id',$supplier_id)
                                            ->where('sub_program_id',$sub_program_id)
                                            ->where('item_category',$item_category)
                                            ->get();
                                            
                if($check_if_category_exist->isEmpty()){

                    $insert_voucher_transaction = db::table('voucher_transaction_draft')
                    ->insert([
                        'voucher_transaction_draft_id' => $voucher_transaction_draft_id,
                        'reference_no'                 => $reference_no,
                        'supplier_id'                  => $supplier_id,
                        'item_category'                => $item_category,
                        'sub_program_id'               => $sub_program_id,
                        'quantity'                     => $quantity,
                        'amount'                       => $amount,
                        'total_amount'                 => $total_amount
                    ]);
    
                    
                    if($insert_voucher_transaction){
                    $count_success++;
                    }
                    
                }else{


                    $update_voucher_transaction_draft = db::table('voucher_transaction_draft')
                                        ->where('reference_no',$reference_no)
                                        ->where('supplier_id',$supplier_id)
                                        ->where('sub_program_id',$sub_program_id)
                                        ->where('item_category',$item_category != '' ? $item_category : '')
                                        ->update([                                            
                                            'reference_no'   => $reference_no,
                                            'supplier_id'    => $supplier_id,
                                            'item_category'  => $item_category,
                                            'sub_program_id' => $sub_program_id,
                                            'quantity'       => $quantity,
                                            'amount'         => $amount,
                                            'total_amount'   => $total_amount
                                        ]);

                    $count_success = count($cart);
                }
             

               
            }
        
        }


        if($count_success == count($cart)){

            $result = json_encode(["message" => 'true']);
        }else{
            $result = json_encode(["message" => 'false']);
        }

        
        return $result;
    }    

    // get payout list (PAYOUT SCREEN)
    public function get_payout_list($supplier_id,$offset){
        $supplier_id      = request('supplier_id');

        $get_batch_payout = db::table('payout_gif_batch')                                
                                ->where('supplier_id',$supplier_id)                                
                                ->orderBy('transac_date','desc')
                                ->skip($offset)
                                ->take(10)                                      
                                ->get();
                                
        $total_paid_payout = db::table('payout_gif_batch as pgb')
                                ->select(db::raw('SUM(amount) as total_paid_payout'))
                                ->leftJoin('payout_gfi_details as pgd','pgd.batch_id','pgb.batch_id')
                                ->where('supplier_id',$supplier_id)                                
                                ->where('iscompleted','1')                                
                                ->orderBy('transac_date','desc')  
                                ->groupBy('pgd.batch_id')                              
                                ->first()->total_paid_payout;    

        return json_encode(["get_batch_payout" => $get_batch_payout, "total_paid_payout" => $total_paid_payout]);
    }

    // get payout list (PAYOUT SUMMARY SCREEN)
    public function get_payout_transaction_list($batch_id,$offset){
        
        
        $get_batch_payout_transaction_list = db::table('payout_gfi_details')                                
                                                ->where('batch_id',$batch_id)                                
                                                ->orderBy('transac_date','desc')
                                                ->skip($offset)
                                                ->take(100000)                                      
                                                ->get();
        $get_voucher_info = [];
        foreach($get_batch_payout_transaction_list as $item){

            $get_reference_no   = db::table('voucher_transaction')
                                            ->where('transaction_id',$item->transaction_id)                                            
                                            ->groupBy('transaction_id')
                                            ->first()->reference_no;

            $get_voucher_info = db::table('voucher as v')
                                ->join('voucher_transaction as vt','v.reference_no','vt.reference_no')
                                ->where('v.reference_no',$get_reference_no)
                                ->groupBy('v.reference_no')
                                ->get();
        }

        return json_encode($get_voucher_info);
    }
    



    // check category draft table
    public function check_if_category_has_draft(){
        $reference_no   = request('reference_no');
        $supplier_id   = request('supplier_id');
        
        $check_if_category_exist = db::table('voucher_transaction_draft')
                                            ->where('reference_no',$reference_no)
                                            ->where('supplier_id',$supplier_id)
                                            
                                            ->get();
        $result = '';

        $existing_category = [];
        if(!$check_if_category_exist->isEmpty()){

            foreach($check_if_category_exist as $value){

                array_push($existing_category,$value->item_category);                
            }


            
            $result = json_encode(["message" => 'true','existing_categories' => $existing_category]);

        }else{
            $result = json_encode(["message" => 'false']);
        }

        return $result;
    
        
    }
    
    public function check_utility($version){
        $check_version = db::table('mobile_utility')
                            ->where('version',$version)
                            ->first();
        $result = '';
        if($check_version){
            $result = json_encode(["message"=>'true',"maintenance"=>$check_version->maintenance , "active" => $check_version->active]);
        }else{
            $result = json_encode(["message"=>'false']);
        }
        return $result;
        
    }



    
    
}

