<?php

namespace App\Modules\MobileApp\Http\Controllers;

use App\Http\Controllers\Controller;
use File;
use Mail;
use DB;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
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
    
        $authenticate = db::table('users')->where('username', $username)->get();
        $get_password = db::table('users')->where('username', $username)->value('password');

        

        $to_email = "";
        $otp_to_send = "";            
        if (!$authenticate->isEmpty()) {

            if(password_verify($password,$get_password)){
                foreach ($authenticate as $authenticate) {
                    $to_email = $authenticate->email;
                    $supplier = db::table('program_permissions as pp')
                              ->select(db::raw("CONCAT(first_name,' ',last_name) as full_name"), 'supplier_id', 'u.user_id')
                              ->join('supplier as s', 's.supplier_id', 'pp.other_info')
                              ->join('users as u', 'u.user_id', 'pp.user_id')
                              ->where('u.user_id', $authenticate->user_id)->first();
                }   

            
                $check_otp = db::table('user_otp')
                                ->where('user_id',$supplier->user_id)
                                ->where('status','1')
                                ->get();

                if(!$check_otp->isEmpty()){
                    foreach($check_otp as $item){
                           $otp_to_send = $item->otp;
                    }
                }else{
                    $otp_to_send = $random_otp;
                    // insert otp to user_otp table
                    db::table('user_otp')->insert([
                        "user_id" => $supplier->user_id,
                        "otp"     => $otp_to_send                    
                    ]);
                }
              
                
                
                Mail::send('MobileApp::otp', ["otp_code" => $otp_to_send], function ($message) use ($to_email, $otp_to_send) {
                    $message->to($to_email)
                            ->subject('DA VMP Mobile')
                            ->from("support.sadd@da.gov.ph");
                });

                return json_encode(array([
                    "Message"     => "true",
                    "OTP"         => $otp_to_send,
                    "email"       => $to_email,
                    "supplier_id" => $supplier->supplier_id,
                    "user_id"     => $supplier->user_id,
                    "full_name"   => $supplier->full_name
                ]));
            }else{
                return json_encode(array(["Message" => "false"]));
            }
        } else {
            return json_encode(array(["Message" => "false"]));
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
        db::table('user_otp')->insert([
            "user_id" => $user_id,
            "otp"     => $random_otp                    
        ]);

          Mail::send('MobileApp::otp', ["otp_code" => $random_otp], function ($message) use ($email, $random_otp) {
              $message->to($email)
                      ->subject('DA VMP Mobile')
                      ->from("webdeveloper01000@gmail.com");
          });
  
          return json_encode(array(["Message" => 'true',
                                    "OTP"     => $random_otp]));
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

    // get scanned vouchers for home screen
    public function get_scanned_vouchers($supplier_id)
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
                 DB::raw("YEAR(transac_date) as year_transac")                       
              )
            ->join('voucher_transaction as vt', 'v.reference_no','vt.reference_no')            
            ->join('voucher_attachments as va', 'va.voucher_details_id','vt.voucher_details_id')
            ->join('programs as p', 'p.program_id','v.program_id')            
            ->where('supplier_id', $supplier_id)  
            ->where('document', 'Farmer with Commodity')            
            ->groupBy('v.reference_no')
            ->orderBy('transac_date', 'DESC')            
            ->get();

      
            foreach ($get_scanned_vouchers as $key => $item) {
                
                $item->base64 = base64_encode(Storage::disk('uploads')->get('/attachments//'. $item->program.'/'.$item->year_transac.'/' . $item->rsbsa_no.'/'.$item->file_name));
                
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

    // get voucher info for farmer profile screen
    public function get_voucher_info()
    {
        //

        $reference_num = request('reference_num');
        $supplier_id   = request('supplier_id');
        
        $get_info      = db::table('voucher as v')
                       ->join('programs as p','p.program_id','v.program_id')
                       ->where('reference_no', $reference_num)->get();
        


        if (!$get_info->isEmpty()) {
            // Compute the balance of voucher    
            $get_voucher = db::table('voucher')->where('reference_no', $reference_num)->first();


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

            $get_program_items   = $this->getProgramItems($supplier_id);
            $get_recent_claiming = $this->get_transactions_history($reference_num);

            return json_encode(array(["Message"       => 'true',
                                      "data"          => $get_info, 
                                      "program_items" => $get_program_items,
                                      "history"       => $get_recent_claiming]));
        }else{
            return json_encode(array(["Message" => 'false']));
        }
    }







    // insert attachment function
    public function insertAttachment($item,$uuid,$voucher_info,$program){
        // insert attachment data to database
        $attachment_uuid        = Uuid::uuid4();
        $front_attachment_uuid  = Uuid::uuid4();
        $back_attachment_uuid   = Uuid::uuid4();

        if ($item->name == 'Valid ID') {
            $id_front = $item->file[0]->front;
            $id_back  = $item->file[0]->back;

            // front page of id 
            $id_front      = str_replace('data:image/jpeg;base64,', '', $id_front);
            $id_front      = str_replace(' ', '+', $id_front);
            $id_front_name = $voucher_info->rsbsa_no . '-' . $item->name . '(front)' . '.jpeg';

            // back page of id
            $id_back      = str_replace('data:image/jpeg;base64,', '', $id_back);
            $id_back      = str_replace(' ', '+', $id_back);
            $id_back_name = $voucher_info->rsbsa_no . '-' . $item->name . '(back)' . '.jpeg';

            $upload_folder  = '/attachments//'. $program.'/'.Carbon::now()->year.'/' . $voucher_info->rsbsa_no;

            // insert front page of id in database
            db::table('voucher_attachments')->insert([
                'attachment_id'      => $front_attachment_uuid,
                'voucher_details_id' => $uuid,
                'document'           => $item->name,
                'file_name'          => $id_front_name,
            ]);

            // insert back page of id in database
            db::table('voucher_attachments')->insert([
                'attachment_id'      => $back_attachment_uuid,
                'voucher_details_id' => $uuid,
                'document'           => $item->name,
                'file_name'          => $id_back_name,
            ]);

            // Check Folder if exist for farmers attachment;
            if (!File::isDirectory($upload_folder)) {
                File::makeDirectory($upload_folder, 0777, true);
                Storage::disk('uploads')->put($upload_folder . '/' . $id_front_name, base64_decode($id_front));
                Storage::disk('uploads')->put($upload_folder . '/' . $id_back_name, base64_decode($id_back));
                
            } else {
          
                Storage::disk('uploads')->put($upload_folder . '/' . $id_front_name, base64_decode($id_front));
                Storage::disk('uploads')->put($upload_folder . '/' . $id_back_name, base64_decode($id_back));                
            }
        } else {

            $image = $item->file;

            $image     = str_replace('data:image/jpeg;base64,', '', $image);
            $image     = str_replace(' ', '+', $image);
            $imageName = $voucher_info->rsbsa_no . '-' . $item->name . '.jpeg';
            
            $upload_folder  = '/attachments//'. $program.'/'.Carbon::now()->year.'/' . $voucher_info->rsbsa_no;
            // insert pictures in database
            db::table('voucher_attachments')->insert([
                'attachment_id'      => $attachment_uuid,
                'voucher_details_id' => $uuid,
                'document'           => $item->name,
                'file_name'          => $imageName,
            ]);
            // Check Folder if exist for farmers attachment;
            if (!File::isDirectory($upload_folder)) {
                // File::makeDirectory($upload_folder, 0777, true);                
                Storage::disk('uploads')->put($upload_folder . '/' . $imageName, base64_decode($image));
            } else {
                Storage::disk('uploads')->put($upload_folder . '/' . $imageName, base64_decode($image));
            }
        }
    }

    //SUBMIT FUNCTION OF CLAIM VOUCHER RRP
    public function submit_voucher_rrp()
    {

        try {

            $uuid         = Uuid::uuid4();
            $voucher_info = json_decode(request('voucher_info'));
            $commodity    = json_decode(request('commodity'));
            $attachments  = json_decode(request('attachments'));

            // insert to voucher transaction table
            db::table('voucher_transaction')->insert(
                [
                    'voucher_details_id'  => $uuid,
                    'reference_no'        => $voucher_info->reference_no,
                    'supplier_id'         => $voucher_info->supplier_id,
                    'sub_program_id'      => $commodity->sub_id,
                    'fund_id'             =>  $voucher_info->fund_id,
                    'quantity'            =>  $commodity->quantity,
                    'amount'              =>  $commodity->fertilizer_amount,
                    'total_amount'        =>  $commodity->total_amount,
                    'latitude'            =>  $voucher_info->latitude,
                    'longitude'           =>  $voucher_info->longitude,
                    'transac_by_id'       =>  $voucher_info->supplier_id,
                    'transac_by_fullname' =>  $voucher_info->full_name,
                ]
            );

            // upload attachments to file server 
            foreach ($attachments as $item) {
                $this->insertAttachment($item,$uuid,$voucher_info,$voucher_info->program);
            }

            //  compute remaining balance
            $compute_remaining_bal = $voucher_info->current_balance - $commodity->total_amount;

            // update  voucher gen table amount_val
            
            db::table('voucher')
                ->where('reference_no', $voucher_info->reference_no)
                ->update([
                    'amount_val'     => $compute_remaining_bal, 
                    'voucher_status' => 'FULLY CLAIMED',                    
                ]);
                
        } catch (\Exception $e) {
            echo json_encode(array(["Message"    => $e->getMessage(), 
                                    "StatusCode" => $e->getCode()]));
        }
    }

    //SUBMIT FUNCTION OF CLAIM VOUCHER CFSMFF
    public function submit_voucher_cfsmff() 
    {

        try {
         
            $voucher_info = json_decode(request('voucher_info'));
            $commodity    = json_decode(request('commodity'));
            $attachments  = json_decode(request('attachments'));

            // insert to voucher transaction table
            
            $voucher_details_uuid = '';
            $sum_total_amount = 0;
            foreach($commodity as $item){
                $voucher_details_uuid = Uuid::uuid4();
                db::table('voucher_transaction')->insert(
                    [
                        'voucher_details_id'  => $voucher_details_uuid,
                        'reference_no'        => $voucher_info->reference_no,
                        'supplier_id'         => $voucher_info->supplier_id,
                        'sub_program_id'      => $item->sub_id,
                        'fund_id'             =>  $voucher_info->fund_id,
                        'quantity'            =>  $item->quantity,
                        'amount'              =>  $item->price,
                        'total_amount'        =>  $item->total_amount,
                        'latitude'            =>  $voucher_info->latitude,
                        'longitude'           =>  $voucher_info->longitude,
                        'transac_by_id'       =>  $voucher_info->supplier_id,
                        'transac_by_fullname' =>  $voucher_info->full_name,
                    ]
                );

                // comp
                $sum_total_amount += $item->total_amount;


            }
        

            // upload attachments to file server 
            foreach ($attachments as $item) {
                $this->insertAttachment($item,$voucher_details_uuid,$voucher_info,$voucher_info->program);
            }

            //  compute remaining balance
            $compute_remaining_bal = $voucher_info->current_balance - $sum_total_amount;

            // update  voucher gen table amount_val
            db::table('voucher')
                ->where('reference_no', $voucher_info->reference_no)
                ->update([
                    'amount_val'     => $compute_remaining_bal, 
                    'voucher_status' => 'PARTIALLY CLAIMED',
                ]);
        } catch (\Exception $e) {
            echo json_encode(array(["Message"    => $e->getMessage(), 
                                    "StatusCode" => $e->getCode()]));
        }
    }

  


    // get program items (rice, egg , etc...)
    public function getProgramItems($supplier_id)
    {

        $get_record = db::table('program_items as pi')
                        ->join('supplier_programs as sp', 'pi.item_id', 'sp.item_id')
                        ->where('supplier_id', $supplier_id)
                        ->get();
                        
        foreach($get_record as $key => $item){
            $item->base64 = base64_encode(file_get_contents(storage_path('/commodities//' .$item->item_profile)));            
        }

        return $get_record;
    }



    
}

