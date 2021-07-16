<?php

namespace App\Modules\MobileApp\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use File;
use Mail;

use DB;
use Ramsey\Uuid\Uuid;


class MobileAppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


 


    // get sign in for login screen
    public function sign_in(Request $request)
    {
        //
        $username = request('username');
        $password = request('password');
        $random_otp = mt_rand(100000, 999999);

        $authenticate = db::table('users')->where('username', $username)->get();
        $get_password = db::table('users')->where('username', $username)->value('password');

        

        $to_email = "";
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


                Mail::send('MobileApp::otp', ["otp_code" => $random_otp], function ($message) use ($to_email, $random_otp) {
                    $message->to($to_email)
                        ->subject('DA VMP Mobile')
                        ->from("support.sadd@da.gov.ph");
                });

                return json_encode(array([
                    "Message" => "true",
                    "OTP" => $random_otp,
                    "email" => $to_email,
                    "supplier_id" => $supplier->supplier_id,
                    "user_id" => $supplier->user_id,
                    "full_name" => $supplier->full_name
                ]));
            }else{
                return json_encode(array(["Message" => "false"]));
            }
        } else {
            return json_encode(array(["Message" => "false"]));
        }
    }

    // get scanned vouchers for home screen
    public function get_scanned_vouchers($supplier_id)
    {



        $get_scanned_vouchers = db::table('voucher as v')
            ->select(
                'v.reference_no',
                DB::raw('DATE(transac_date) as CLAIMED_DATE'),
                DB::raw("CONCAT(first_name,' ',middle_name,' ',last_name) as fullname")
            )
            ->join('voucher_transaction as vt', 'v.reference_no', 'vt.reference_no')
            ->where('supplier_id', $supplier_id)
            ->orderBy('transac_date', 'DESC')
            ->get();



        // ->orWhere('VOUCHER_STATUS','NOT FULLY CLAIMED')

        return json_encode($get_scanned_vouchers);
    }

    public function get_transactions_history($reference_num){
        $get_voucher_transactions = db::table('voucher_transaction')->where('reference_no', $reference_num)->get();
            return $get_voucher_transactions; 
    }

    // get voucher info for farmer profile screen
    public function get_voucher_info()
    {
        //

        $reference_num = request('reference_num');
        $supplier_id = request('supplier_id');
        
        $get_info = db::table('voucher as v')
                    ->join('programs as p','p.program_id','v.program_id')
                    ->where('reference_no', $reference_num)->get();
        


        if (!$get_info->isEmpty()) {
            // Compute the balance of voucher    
            $get_voucher = db::table('voucher')->where('reference_no', $reference_num)->first();


            $get_region = $get_voucher->reg;
            $get_province = $get_voucher->prv;
            $get_municipality = $get_voucher->mun;
            $get_brgy = $get_voucher->brgy;


            $get_geo_map =  db::table('geo_map')
                ->where('reg_code', $get_region)
                ->where('prov_code', $get_province)
                ->where('mun_code', $get_municipality)
                ->where('bgy_code', $get_brgy)
                ->first();

            $check_balance = $get_voucher->amount_val;

            foreach($get_info as $item){          
                $item->Available_Balance = $check_balance;
                $item->Region = $get_geo_map->reg_name;
                $item->Province = $get_geo_map->prov_name;
                $item->Municipality = $get_geo_map->mun_name;
                $item->Barangay = $get_geo_map->bgy_name;
            }

            $get_program_items = $this->getProgramItems($supplier_id);
            $get_recent_claiming = $this->get_transactions_history($reference_num);

            return json_encode(array(["Message" => 'true', "data" => $get_info, "program_items" => $get_program_items,'history'=>$get_recent_claiming]));
        }else{
            return json_encode(array(["Message" => 'false']));
        }
    }





    //  //  SUBMIT FUNCTION OF Claim Voucher OLD
    //  public function submit_voucher(){

    //     try{

    //             $reference_num = request('reference_num');        
    //             $supplier_id = request('supplier_id');        
    //             $images_count = request('images_count');        
    //             $commodities = json_encode(request('commodities'));
    //             $decode = json_decode($commodities,true);
    //             $rsbsa_ctrl_no = $this->vouchergen_model->where('REFERENCE_NO',$reference_num)->first()->RSBSA_CTRL_NO;

    //             $update_voucher_gen = new VoucherGenModel();
    //             $commodities_total_amount = 0;
    //             // store commodities
    //             foreach($decode as $item){
    //                 $decoded_item = json_decode($item);

    //                 $store_commodities = new CommodityModel();


    //                 $commodity = $decoded_item->commodity;
    //                 $unit = $decoded_item->unit;                        
    //                 $quantity = $decoded_item->quantity;                        
    //                 $amount = $decoded_item->amount;                        
    //                 $total_amount = $decoded_item->total_amount;                        

    //                 $commodities_total_amount += $total_amount;

    //                 $store_commodities->fill([
    //                     "commodity" =>  $commodity,
    //                     "quantity" => $quantity,
    //                     "unit" => $unit,                                            
    //                     "amount" => $total_amount,
    //                     "REFERENCE_NO" => $reference_num,
    //                     "RSBSA_CTRL_NO" => $rsbsa_ctrl_no,
    //                     "DISTRIBUTOR_ID" => $supplier_id,
    //                     "SUPPLIER_CODE" => $supplier_id,
    //                     "SUPPLIER_GROUP" => $supplier_id

    //                 ])->save();


    //             }




    //             $compute_balance = 0;

    //             $current_balance =  $this->vouchergen_model->where('REFERENCE_NO',$reference_num)->first()->AMOUNT;
    //             $compute_balance = $current_balance - $commodities_total_amount  ;

    //             $get_date = Carbon::now();
    //             $update_voucher_gen->where('REFERENCE_NO',$reference_num)->update(
    //                 [
    //                     "AMOUNT" => $compute_balance,
    //                     "VOUCHER_STATUS" => "CLAIMED",
    //                     "CLAIMED_DATE" => $get_date->toDateTimeString(),
    //                     "SUPPLIER_CODE" => $supplier_id,
    //                 ]);



    //             $document_type_value = '';

    //             // upload and store 
    //             for($i = 0 ; $i < $images_count ; $i++){
    //                 $image = request()->input('image'.$i);
    //                 $document_type = request('document_type'.$i);
    //                 $image = str_replace('data:image/jpeg;base64,', '', $image);
    //                 $image = str_replace(' ', '+', $image);
    //                 $imageName = $reference_num .'_'.$i. '.jpeg';

    //                 $store_attachments = new  AttachmentModel();

    //                 if($document_type == 3)
    //                     $document_type_value = 'Picture of other documents or attachments';
    //                 else if ($document_type == 2)
    //                     $document_type_value = 'Picture of ID Presented and Signature';
    //                 else if ($document_type == 1 )
    //                     $document_type_value = 'Picture of farmer holding interventions';


    //                  $store_attachments->fill([
    //                         "att_file" =>  $imageName,
    //                         "requirement" => $document_type,
    //                         "filetitle" => $document_type_value,                                            
    //                         "REFERENCE_NO" => $reference_num,
    //                         "RSBSA_CTRL_NO" => $rsbsa_ctrl_no,
    //                         "imglink" => URL::to('/').'//storage//'. '/uploads//'.$imageName,
    //                         "SUPPLIER_CODE" => $supplier_id,
    //                         "DISTRIBUTOR_ID" => $supplier_id
    //                 ])->save();


    //                 File::put(storage_path(). '/uploads//' . $imageName, base64_decode($image));            
    //             }


    //             echo json_encode(array(["Message"=>'true']));


    //     }catch(\Exception $e){
    //         echo json_encode(array(["Message"=>$e->getMessage(),"StatusCode" => $e->getCode()]));
    //     }

    // }


    // insert attachment function
    public function insertAttachment($item,$uuid,$voucher_info){
        // insert attachment data to database
        $attachment_uuid = Uuid::uuid4();
        $front_attachment_uuid = Uuid::uuid4();
        $back_attachment_uuid = Uuid::uuid4();

        if ($item->name == 'Valid ID') {
            $id_front = $item->file[0]->front;
            $id_back = $item->file[0]->back;

            
            db::table('voucher_transaction')
                    ->where(  'voucher_details_id' , $uuid)
                    ->update(
                        [
                            'latitude' =>  $item->latitude,
                            'longitude' =>  $item->longitude, 
                        ]);

            // front page of id 
            $id_front = str_replace('data:image/jpeg;base64,', '', $id_front);
            $id_front = str_replace(' ', '+', $id_front);
            $id_front_name = $voucher_info->rsbsa_no . '-' . $item->name . '(front)' . '.jpeg';

            // back page of id
            $id_back = str_replace('data:image/jpeg;base64,', '', $id_back);
            $id_back = str_replace(' ', '+', $id_back);
            $id_back_name = $voucher_info->rsbsa_no . '-' . $item->name . '(back)' . '.jpeg';

            $upload_folder  = storage_path() . '/attachments//' . $voucher_info->rsbsa_no;

            // insert front page of id in database
            db::table('voucher_attachments')->insert([
                'attachment_id' => $front_attachment_uuid,
                'voucher_details_id' => $uuid,
                'document' => $item->name,
                'file_name' => $id_front_name,
            ]);

            // insert back page of id in database
            db::table('voucher_attachments')->insert([
                'attachment_id' => $back_attachment_uuid,
                'voucher_details_id' => $uuid,
                'document' => $item->name,
                'file_name' => $id_back_name,
            ]);

            // Check Folder if exist for farmers attachment;
            if (!File::isDirectory($upload_folder)) {
                File::makeDirectory($upload_folder, 0777, true);
                File::put($upload_folder . '/' . $id_front_name, base64_decode($id_front));
                File::put($upload_folder . '/' . $id_back_name, base64_decode($id_back));
            } else {
                File::put($upload_folder . '/' . $id_front_name, base64_decode($id_front));
                File::put($upload_folder . '/' . $id_back_name, base64_decode($id_back));
            }
        } else {

            $image = $item->file;

            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = $voucher_info->rsbsa_no . '-' . $item->name . '.jpeg';

            $upload_folder  = storage_path() . '/attachments//' . $voucher_info->rsbsa_no;

            // insert pictures in database
            db::table('voucher_attachments')->insert([
                'attachment_id' => $attachment_uuid,
                'voucher_details_id' => $uuid,
                'document' => $item->name,
                'file_name' => $imageName,
            ]);
            // Check Folder if exist for farmers attachment;
            if (!File::isDirectory($upload_folder)) {
                File::makeDirectory($upload_folder, 0777, true);
                File::put($upload_folder . '/' . $imageName, base64_decode($image));
            } else {
                File::put($upload_folder . '/' . $imageName, base64_decode($image));
            }
        }
    }

    //SUBMIT FUNCTION OF CLAIM VOUCHER RRP
    public function submit_voucher_rrp()
    {

        try {
            $uuid = Uuid::uuid4();

            $voucher_info = json_decode(request('voucher_info'));
            $commodity = json_decode(request('commodity'));
            $attachments = json_decode(request('attachments'));

            // insert to voucher transaction table
            db::table('voucher_transaction')->insert(
                [
                    'voucher_details_id' => $uuid,
                    'reference_no' => $voucher_info->reference_no,
                    'supplier_id' => $voucher_info->supplier_id,
                    'sub_program_id' => $commodity->sub_id,
                    'fund_id' =>  $voucher_info->fund_id,
                    'quantity' =>  $commodity->quantity,
                    'amount' =>  $commodity->fertilizer_amount,
                    'total_amount' =>  $commodity->total_amount,
                    'transac_by_id' =>  $voucher_info->supplier_id,
                    'transac_by_fullname' =>  $voucher_info->full_name,
                ]
            );

            // upload attachments to file server 
            foreach ($attachments as $item) {
                $this->insertAttachment($item,$uuid,$voucher_info);
            }

            //  compute remaining balance
            $compute_remaining_bal = $voucher_info->current_balance - $commodity->total_amount;

            // update  voucher gen table amount_val
            db::table('voucher')
                ->where('reference_no', $voucher_info->reference_no)
                ->update([
                    'amount_val' => $compute_remaining_bal, 
                    'voucher_status' => 'FULLY CLAIMED',
                ]);
        } catch (\Exception $e) {
            echo json_encode(array(["Message" => $e->getMessage(), "StatusCode" => $e->getCode()]));
        }
    }

    // resend OTP
    public function resendOTP()
    {

        $email = request('email');
        $random_otp = mt_rand(100000, 999999);

        Mail::send('MobileApp::otp', ["otp_code" => $random_otp], function ($message) use ($email, $random_otp) {
            $message->to($email)
                ->subject('DA VMP Mobile')
                ->from("webdeveloper01000@gmail.com");
        });

        return json_encode(array(["Message" => 'true', "OTP" => $random_otp]));
    }


    // get program items (rice, egg , etc...)
    public function getProgramItems($supplier_id)
    {

        $get_record = db::table('program_items as pi')
                        ->join('supplier_programs as sp', 'pi.item_id', 'sp.item_id')
                        ->where('supplier_id', $supplier_id)
                        ->get();

        foreach ($get_record as $key => $item) {
            $item->base64 = base64_encode(file_get_contents(storage_path('/commodities//' . $item->item_profile)));
        }

        return $get_record;
    }
}

