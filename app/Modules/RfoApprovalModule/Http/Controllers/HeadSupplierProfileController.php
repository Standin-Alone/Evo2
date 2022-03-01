<?php

namespace App\Modules\RfoApprovalModule\Http\Controllers;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\RfoApprovalModule\Models\HeadSupplierProfileModel;

class HeadSupplierProfileController extends Controller
{
    public function __construct(Request $request){

        $this->head_supplier_model = new HeadSupplierProfileModel;

    }

    public function continuation_of_creation_for_supplier_profile_view(Request $request){

        $supplier_id    = $request->user_id;
        $code           = $request->code;

        $update_access = $this->head_supplier_model->get_access_query($supplier_id, $code);

        $banks = $this->head_supplier_model->get_SupplierBank();

        if($update_access == "[]"){

                return view("RfoApprovalModule::404_error");

        }
        else{
                foreach($update_access as $ua){

                    $regions = $this->head_supplier_model->get_SupplierRegion($ua->reg);
                    
                    $supplier_profile = $this->head_supplier_model->get_SupplierProfileDetails($supplier_id);
                        
                    return view("RfoApprovalModule::create_supplier_profile", compact(['supplier_id', 'supplier_profile', 'regions','banks']));

                }
        }

    }

    public function get_supplier_province($reg_code){
        
        $provinces = $this->head_supplier_model->get_SupplierProvince($reg_code);

        return response()->json($provinces);

    }

    public function get_supplier_city($reg_code, $prov_code){

        $cities = $this->head_supplier_model->get_SupplierMunicipalities($reg_code, $prov_code);

        return response()->json($cities);

    }

    public function get_supplier_barangay($reg_code, $prov_code, $mun_code){

        $barangay = $this->head_supplier_model->get_SupplierBarangay($reg_code, $prov_code, $mun_code);

        return response()->json($barangay);

    }

    public function send_update_profile(Request $request){

        // dd($request->all());

        $region             = $request->main_region;
        $supplier_id        = $request->main_supplier_id;
        $supplier_group_id  = $request->main_supplier_group_id;
        $fname              = $request->main_first_name;
        $mname              = $request->main_middle_name;
        $lname              = $request->main_last_name;
        $ename              = $request->main_ext_namel;
        $email              = $request->main_email;
        $contact            = $request->main_contact_no ;
        $address            = $request->main_complete_address;
        $bank_long_name     = $request->select_main_owner_bank_long_name;
        $bank_short_name    = $request->select_main_owner_bank_short_name;
        $acc_name           = $request->main_bank_account_name;
        $acc_no             = $request->main_bank_account_no;
        $phone_no           = $request->main_phone_no;
        $supplier_group     = $request->main_supplier_group;
        $supplier_name      = $request->main_supplier_name;
        $reg                = $request->main_select_supplier_region;
        $prv                = $request->main_select_province;
        $mun                = $request->main_select_city;
        $brgy               = $request->main_select_supplier_barangay;
        $business_permit    = $request->main_business_permit;
        $reg_code           = $request->main_reg_code;
        $prov_code          = $request->main_prov_code;
        $mun_code           = $request->main_mun_code;
        $brgy_code          = $request->main_brgy_code;
        $isVerified         = "0";
        $supplier_type      = $request->main_supplier_type;

        $supplier_fullname = $fname.' '.$lname.' '.$ename;

        $geo_code = $this->head_supplier_model->get_geo_code_query($reg_code, $prov_code, $mun_code, $brgy_code);

        $update_status      = "1";

        $rfo_approver = $this->head_supplier_model->get_RFO_approver_email_and_details_query($region);

        $rfo_email_data   = [];
        $rfo_role_data    = [];
        $date_created = Carbon::now('GMT+8')->toDateTimeString();

        foreach($rfo_approver as $ra){
            
            array_push($rfo_email_data, $ra->email);
            array_push($rfo_role_data, $ra->role);

        }

        // $rfo_approval_email = $data;

        // insert first update profile in temp_supplier table
        $this->head_supplier_model->insert_first_update_profile_query(
                                                                        $supplier_id,
                                                                        $supplier_name, 
                                                                        $supplier_group_id,
                                                                        $address,
                                                                        $email,
                                                                        $contact,
                                                                        $business_permit,
                                                                        $fname,
                                                                        $mname == "" ? $mname = null : $mname,
                                                                        $lname,
                                                                        $ename == "" ? $ename = null : $ename,
                                                                        $phone_no,
                                                                        $geo_code,
                                                                        $reg_code,
                                                                        $prov_code,
                                                                        $mun_code,
                                                                        $brgy_code,
                                                                        $bank_long_name,
                                                                        $bank_short_name,
                                                                        $acc_no,
                                                                        $acc_name,
                                                                        $isVerified,
                                                                        $supplier_type,
                                                                        $update_status
                                                                    );

        //Update supplier upsdate_status column
        DB::table('supplier')->where('supplier_id', '=', $supplier_id)->update(['update_status' => 1]);

        //Send mail notification to RFO Approval for approval of Head Supplier Profile Updating
        foreach($rfo_approver as $ra){
            $rfo_fullname = $ra->first_name.' '.$ra->last_name.' '.$ra->ext_name;
            $rfo_email_data = $ra->email;
            $rfo_role_data = $ra->role;

            $this->head_supplier_model->head_supplier_profile_updating_notification(
                                                                                        $rfo_email_data,
                                                                                        $rfo_fullname,
                                                                                        $rfo_role_data,
                                                                                        $supplier_name,
                                                                                        $supplier_fullname,
                                                                                        $email,
                                                                                        $contact,
                                                                                        $reg,
                                                                                        $date_created,
                                                                                        $supplier_type
                                                                                    );

        }

        $success_response = ['success'=>true, 'message'=>'Sending notification to RFO Approval', 'auth'=>false];
        return response()->json($success_response, 200);

    }

    public function view_temp_supplier_profile(Request $request){

        $supplier_id = $request->supplier_id;

        // Get temp_supplier table datas
        $temp_supp = $this->head_supplier_model->get_temp_supplier_tbl_query($supplier_id);

        return view("RfoApprovalModule::list_of_supplier_and_merchants", compact($temp_supp));

    }

    public function update_supplier_profile(Request $request){

        // dd($request->all());

        $supplier_id = $request->update_supplier_id;
        $supplier_name = $request->updateSupplierName;
        $supplier_group_id = $request->update_supplier_group_id;
        $address = $request->update_complete_address;
        $email = $request->update_email;
        $contact_no = $request->update_contact_no;
        $business_permit = $request->update_business_permit;
        $owner_first_name = $request->update_first_name;
        $owner_middle_name = $request->update_middle_name;
        $ownerlast_name = $request->update_last_name;
        $owner_ext_name = $request->update_ext_name;
        $owner_phone_no = $request->update_phone_no;
        $geo_code = $request->update_geo_code;
        $reg = $request->updateSelectSupplierRegion;
        $prv = $request->updateSelectProvince;
        $mun = $request->updateSelectCity;
        $brgy = $request->updateSelectSupplierBarangay;
        $bank_long_name = $request->update_owner_bank_name;
        $bank_short_name = $request->update_owner_bank_short_name;
        $bank_account_no = $request->update_bank_account_no;
        $bank_account_name = $request->update_bank_account_name;
        $isVerified = "1";
        $supplier_type = $request->rfo_approver_update_supplier_type;

        // when the approver update the head branch/ supplier branch profile
        $update_status = "0";

        
        // Get temp_supplier table datas
        $temp_supp = $this->head_supplier_model->get_temp_supplier_tbl_query($supplier_id);

        // update temp_supplier table update status column
        $this->head_supplier_model->update_temp_supplier_tbl_query($supplier_id, $update_status);
        
        // Get supplier table
        $head_supp_user = $this->head_supplier_model->get_first_access_supplier_profile_code_query($supplier_id);

        foreach($head_supp_user as $u){
            foreach($temp_supp as $ts){
                // if first access supplier profile code column in users_tbl is not null AND update_status column in supplier_tbl is [1]active;
                if(($u->first_access_supplier_profile_code != null) && ($ts->update_status == '1')){
                    
                    // Change first_access_supplier_profile_code to NULL
                    $supplier_profile_update_status = null; 
    
                    // Get fullname
                    $fullname = $u->first_name.' '.$u->last_name.' '.$u->ext_name;
    
                    $date_created = Carbon::now('GMT+8')->toDateTimeString();
                    
                    // $update_status = "0";

                    // Update head supplier profile
                    $this->head_supplier_model->update_supplier_profile_query($supplier_id, $supplier_name, $address, $reg, $prv, $mun, $brgy, $business_permit, $email, $contact_no, $owner_first_name, $owner_middle_name, $ownerlast_name, $owner_ext_name, $owner_phone_no, $geo_code, $bank_long_name, $bank_short_name, $bank_account_name, $bank_account_no, $isVerified, $supplier_type, $update_status);
    
                    // Update users first update of supplier Profile status
                    $this->head_supplier_model->update_user_first_update_supplier_profile_status_query($supplier_id, $supplier_profile_update_status);
            
                    // Send mail notification to head supplier
                    $this->head_supplier_model->approved_update_head_supplier_profile_notification(
                                                                                                    $u->email, 
                                                                                                    $fullname,
                                                                                                    $u->role,
                                                                                                    $u->username,
                                                                                                    $date_created
                                                                                                );
    
                    // return view('update_success_page');

                    // return view('RfoApprovalModule::first_update_profile_success_page');

                    
                    $success_response = ["success" => true, "message" => "Profile update successfully!"];
                    return response()->json($success_response, 200);
                
                }else{
                    $error_response = ["error" => true, "message" => "Error, Can't update Profile!"];
                    return response()->json($success_response, 200);
                }
            }

            // if($u->first_access_supplier_profile_code == null){

            //     return redirect()->route('update_profile_page_not_found');

            // }
            // if($u->first_access_supplier_profile_code == '[]'){

            //     return redirect()->route('update_profile_page_not_found');

            // }

        }
    }

    public function first_profie_update_success_page(){

        // dd($currentURL = URL::current());

        return view("RfoApprovalModule::first_update_profile_success_page");

    }

    public function page_404_status(){

        return view("RfoApprovalModule::404_error");

    }

    // public function create_supplier_profile(){

    //     $params = [
    //         'supplier_id' => $request->supplier_id,
    //         'suppliergroup' => $request->selectSupplierGroup,
    //         'SupplierName' => $request->txtSupplierName,
    //         'SupplierAddress' => $request->txtSupplierAddress,
    //         'SupplierRegion' => $request->selectSupplierRegion,
    //         'SupplierProvince' => $request->selectSupplierProvince,
    //         'SupplierCity' => $request->selectSupplierCity,
    //         'SupplierBarangay' => $request->selectSupplierBarangay,
    //         'SupplierBusinessPermit' => $request->txtSupplierBusinessPermit,
    //         'SupplierEmail' => $request->txtSupplierEmail,
    //         'SupplierContact' => $request->txtSupplierContact,
    //         'OwnerFirstName' => $request->txtOwnerFirstName,
    //         'OwnerMiddleName' => $request->txtOwnerMiddleName,
    //         'OwnerLastName' => $request->txtOwnerLastName,
    //         'OwnerExtName' => $request->txtOwnerExtName,
    //         'bankcode' => $request->selectOwnerBankName,
    //         'banklongname' => $request->txtbanklongname,
    //         'OwnerAcctName' => $request->txtOwnerAcctName,
    //         'OwnerAcctNo' => $request->txtOwnerAcctNo,
    //         'OwnerPhoneNo' => $request->txtOwnerPhoneNo,
    //         'actionbutton' => $request->actionbutton
    //     ];

    //     if($params['actionbutton'] == "INSERT"){
    //         $this->QuerySupplierProfile->insert_SupplierProfile($params);  
    //     }

    //     return view("SupplierProfile::create_supplier_profile");

    // }
}
