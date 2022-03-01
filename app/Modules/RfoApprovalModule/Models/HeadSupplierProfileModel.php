<?php

namespace App\Modules\RfoApprovalModule\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\RfoApprovalModule\Http\Controllers\MailController;

class HeadSupplierProfileModel extends Model
{
    use HasFactory;

    public function get_SupplierProfileDetails($supplier_id){

        $supplier_tbl = DB::table('supplier as s')
                                ->select("s.supplier_id", "s.supplier_name", "s.supplier_group_id", "sg.group_name", "s.address", "s.email", "s.contact",
                                         "s.business_permit", "s.owner_first_name", "s.owner_middle_name", "s.owner_last_name", "s.owner_ext_name", "s.owner_phone", "s.geo_code",
                                         "s.reg", "g.reg_name", "s.prv", "g.prov_name", "s.mun", "g.mun_name", "s.brgy", "g.bgy_name",
                                         "sg.group_name", 
                                         "s.bank_long_name", "s.bank_short_name", "s.bank_account_no", "s.bank_account_name", "s.date_created", "s.supplier_type")
                                ->leftJoin('supplier_group as sg', 'sg.supplier_group_id', 's.supplier_group_id')
                                ->leftJoin('geo_map as g','g.geo_code','s.geo_code')
                                ->leftJoin('users as u', 'u.user_id' , '=' ,'s.supplier_id')
                                ->leftJoin('supplier_srn as ss', 'ss.supplier_id', '=', 's.supplier_id')
                                ->where('s.supplier_id', '=', $supplier_id)
                                // ->where('ss.srn', '=', $srn)
                                ->where('u.status', '=', '1')
                                ->where('u.approval_status', '=', '1')
                                ->get();


        if( $supplier_tbl == "[]"){

            $users_tbl = DB::table('program_permissions as pp')
                                ->select(
                                            'pp.program_id', 'p.title', 'p.shortname as program_shortname', 
                                            'u.user_id', 'u.company_name', 'u.company_address as address', 'u.email', 'u.username', 
                                            'u.first_name as owner_first_name', 'u.middle_name as owner_middle_name', 'u.last_name as owner_last_name', 'u.ext_name as owner_ext_name', 
                                            'u.contact_no as contact',
                                            'u.reg', 'gm.reg_name', 'gr.shortname', 
                                            'u.status', 'u.approval_status',
                                            'r.role',
                                            "s.owner_phone", "s.bank_long_name", "s.bank_short_name", "s.bank_account_no", "s.bank_account_name"
                                        )
                                ->leftJoin('roles as r', 'pp.role_id', '=', 'r.role_id')
                                ->leftJoin('users as u','pp.user_id', '=', 'u.user_id')
                                ->leftJoin('programs as p', 'p.program_id', '=', 'pp.program_id')
                                ->leftJoin('geo_map as gm', 'gm.geo_code', '=', 'u.geo_code')
                                ->leftJoin('geo_region as gr', 'gr.code_reg' ,'=', 'u.geo_code')
                                ->leftJoin('supplier_srn as ss', 'ss.supplier_id', '=', 'u.user_id')
                                ->leftJoin('supplier as s', 's.supplier_id', '=', 'u.user_id')
                                ->where('u.user_id', '=', $supplier_id)
                                ->where('ss.srn', '=', $srn)
                                ->where('u.status', '=', '1')
                                ->where('u.approval_status', '=', '1')
                                ->groupBy('u.user_id')
                                ->get();

            return $users_tbl;

        }else{

            return $supplier_tbl;

        }

        
        // $query = DB::table('supplier as s')
        //                         ->select(DB::raw("s.supplier_id,s.supplier_name,s.supplier_group_id,sg.group_name,s.address,s.email,s.contact,
        //                         s.business_permit,s.owner_first_name,s.owner_middle_name,s.owner_last_name,s.owner_ext_name,s.owner_phone,s.geo_code,
        //                         s.reg,g.reg_name,s.prv,g.prov_name,s.mun,g.mun_name,s.brgy,g.bgy_name,s.bank_long_name,s.bank_short_name,
        //                         AES_DECRYPT(s.bank_account_no,'".session('private_secret_key')."') as bank_account_no,s.bank_account_name,s.date_created"))
        //                         ->leftJoin('supplier_group as sg','sg.supplier_group_id','s.supplier_group_id')
        //                         ->leftJoin('geo_map as g','g.geo_code','s.geo_code')
        //                         ->where('s.supplier_id', '=',$supplier_id)
        //                         ->get();  

        // return $query;

    }

    public function get_first_access_supplier_profile_code_query($supplier_id){

       $query = DB::table('program_permissions as pp')
                            ->select(
                                        'u.user_id', 'u.email', 'u.username', 'u.first_name', 'u.middle_name', 'u.last_name', 'u.ext_name', 'u.contact_no',
                                        'u.reg', 'gm.reg_name', 'gr.shortname', 
                                        'r.role',
                                        "u.first_access_supplier_profile_code"
                                    )
                            ->leftJoin('roles as r', 'pp.role_id', '=', 'r.role_id')
                            ->leftJoin('users as u','pp.user_id', '=', 'u.user_id')
                            ->leftJoin('programs as p', 'p.program_id', '=', 'pp.program_id')
                            ->leftJoin('geo_map as gm', 'gm.geo_code', '=', 'u.geo_code')
                            ->leftJoin('geo_region as gr', 'gr.code_reg' ,'=', 'u.geo_code')
                            ->where('u.user_id', '=', $supplier_id)
                            ->groupBy('u.user_id')
                            ->get();

        return $query;

    }

    public function get_SupplierGroup($supplier_id){

        // $query = DB::table('supplier_group as sg')
        //                     ->select('sg.supplier_group_id', 'sg.group_name', 'sg.address', 'sg.email')
        //                     ->where('')
        //                     // ->groupBy('group_name')
        //                     ->get();   

        // return $query;

    }

    public function get_access_query($supplier_id, $code){

        // if($code != null || $code != ""){
            $query = DB::table('users as u')
                            ->select('u.user_id', 'ss.srn', 'u.status', 'u.approval_status'
                            , 'u.first_access_supplier_profile_code', 'u.reg'
                            )
                            ->leftJoin('supplier_srn as ss', 'ss.supplier_id', '=', 'u.user_id')
                            ->where('u.user_id', '=', $supplier_id)
                            // ->where('ss.srn', '=', $srn)
                            ->where('u.status', '=', '1')
                            ->where('u.approval_status', '=', '1')
                            ->where('u.first_access_supplier_profile_code', '=', $code)
                            ->get();

            return $query;
        // }
        // elseif($code == null ){
        //     $query = false;
        //     return $query;
        // }

    }

    public function get_main_supplier_query($supplier_id, $srn){

        $query = DB::table('program_permissions as pp')
                            ->select(
                                     'pp.program_id', 'p.title', 'p.shortname as program_shortname', 
                                     'u.user_id', 'u.company_name', 'u.company_address', 'u.email', 'u.username', 'u.first_name', 'u.middle_name', 'u.last_name', 'u.ext_name', 
                                     'u.contact_no',
                                     'gm.reg_name', 'gr.shortname', 
                                     'u.status', 'u.approval_status',
                                     'r.role'
                                     )
                            ->leftJoin('roles as r', 'pp.role_id', '=', 'r.role_id')
                            ->leftJoin('users as u','pp.user_id', '=', 'u.user_id')
                            ->leftJoin('programs as p', 'p.program_id', '=', 'pp.program_id')
                            ->leftJoin('geo_map as gm', 'gm.geo_code', '=', 'u.geo_code')
                            ->leftJoin('geo_region as gr', 'gr.code_reg' ,'=', 'u.geo_code')
                            ->leftJoin('supplier_srn as ss', 'ss.supplier_id', '=', 'u.user_id')
                            ->where('u.user_id', '=', $supplier_id)
                            ->where('ss.srn', '=', $srn)
                            ->where('u.status', '=', '1')
                            ->where('u.approval_status', '=', '1')
                            ->groupBy('u.user_id')
                            ->get();

        return $query;

    }

    public function get_SupplierRegion($reg){

        $session_reg_code = sprintf("%02d", session('reg_code'));

        $query = DB::table('geo_map')
                            ->select("reg_code", "reg_name")
                            ->where('reg_code', '=', $reg)
                            ->groupBy('reg_name','reg_code')
                            ->get();      

        return $query;

    }

    public function get_SupplierProvince($reg_code){

        $query = DB::select("CALL get_provinces(" . $reg_code . ")");

        return $query;
   
    }

    public function get_SupplierMunicipalities($reg_code, $prov_code){

        $query = DB::select("CALL get_municipalities(" . $reg_code . ", ". $prov_code .")");

        return $query;
   
    }


    public function get_SupplierBarangay($reg_code, $prov_code, $mun_code){

        $query = DB::select("CALL get_barangay(" . $reg_code . ", ". $prov_code .", ". $mun_code .")");

        return $query;

    }

    public function get_geo_code_query($reg_code, $prov_code, $mun_code, $brgy_code){

        $query = DB::table('geo_map')
                        ->select("geo_code")
                        ->where('reg_code', '=', $reg_code)
                        ->where('prov_code', '=', $prov_code)
                        ->where('mun_code', '=', $mun_code)
                        ->where('bgy_code', '=', $brgy_code)
                        ->get();

        return $query;

    }

    public function get_SupplierBank(){

        $query = DB::table('banks')
                            ->select(DB::raw("shortname,name"))
                            ->where('shortname','<>','')
                            ->groupBy('name')
                            ->get();      

        return $query;

    }

    public function get_RFO_approver_email_and_details_query($region){

        // [4]RFO Program Focal, [8]RFO Reviewer, [10]RFO Approver
        $role_id = ['4', '8', '10'];

        // get RFO Approval email and details
        $query = DB::table('program_permissions as pp')
                        ->select('u.user_id', 'u.email', 'u.username', 'u.first_name', 'u.middle_name', 'u.last_name', 'u.ext_name', 'r.role_id', 'r.role', 'u.reg', 'u.prov', 'gm.reg_name', 'gr.shortname')
                        ->leftJoin('roles as r', 'pp.role_id', '=', 'r.role_id')
                        ->leftJoin('users as u','pp.user_id', '=', 'u.user_id')
                        ->leftJoin('geo_map as gm', 'gm.geo_code', '=', 'u.geo_code')
                        ->leftJoin('geo_region as gr', 'gr.code_reg' ,'=', 'u.geo_code')
                        ->where('u.reg', '=', $region)
                        ->where('r.role', '=', "RFO Approver v2")
                        ->get();

        return $query;

    }

    // public function insert_SupplierProfile($params){

    //     $query = DB::transaction(function() use(&$params){

    //                 DB::table('supplier')->insert([
    //                                                 'supplier_id' => Uuid::uuid4(),
    //                                                 'supplier_group_id' => $params['suppliergroup'],
    //                                                 'supplier_name' => $params['SupplierName'],
    //                                                 'address' => $params['SupplierAddress'],
    //                                                 'geo_code' => 1,
    //                                                 'reg' => $params['SupplierRegion'],
    //                                                 'prv' => $params['SupplierProvince'],
    //                                                 'mun' => $params['SupplierCity'],
    //                                                 'brgy' => $params['SupplierBarangay'],
    //                                                 'business_permit' => $params['SupplierBusinessPermit'],
    //                                                 'email' => $params['SupplierEmail'],
    //                                                 'contact' => $params['SupplierContact'],
    //                                                 'owner_first_name' => $params['OwnerFirstName'],
    //                                                 'owner_middle_name' => $params['OwnerMiddleName'],
    //                                                 'owner_last_name' => $params['OwnerLastName'],
    //                                                 'owner_ext_name' => $params['OwnerExtName'],            
    //                                                 'bank_long_name' => $params['banklongname'],
    //                                                 'bank_short_name' => $params['bankcode'],
    //                                                 'bank_account_no' => DB::raw("AES_ENCRYPT('".$params['OwnerAcctNo']."', '".session('private_secret_key')."')"),
    //                                                 'bank_account_name' => $params['OwnerAcctName'],               
    //                                                 'owner_phone' => $params['OwnerPhoneNo'],
    //                                                 'created_by_id' => session('user_id'),
    //                                                 'created_agency' => session('agency_loc'),
    //                                                 'created_by_fullname' => session('user_fullname'),
    //                                                 'date_created' => Carbon::now('GMT+8')
    //                                             ]);
                
    //             });

    //     return $query;

    // }

    public function insert_first_update_profile_query($supplier_id, $supplier_name, $supplier_group_id, $address, $email, $contact, $business_permit, $fname, $mname, $lname, $ename, $phone_no, $geo_code, $reg_code, $prov_code, $mun_code, $brgy_code, $bank_long_name, $bank_short_name, $acc_no, $acc_name, $isVerified, $supplier_type, $update_status){

        foreach($geo_code as $g){


            $private_secret_key = '3273357538782F413F4428472B4B6250655368566D5971337436773979244226452948404D635166546A576E5A7234753778214125442A462D4A614E645267556A586E327235753778214125442A472D4B6150645367566B59703373367639792F423F4528482B4D6251655468576D5A7134743777217A25432646294A404E635166546A576E5A7234753777217A25432A462D4A614E645267556B58703273357638792F413F4428472B4B6250655368566D597133743677397A2443264529482B4D6251655468576D5A7134743777397A24432646294A404E635266556A586E3272357538782F4125442A472D4B6150645367566B59703373367639792442264428472B4B6250655368566D5971337436773979244226452948404D635166546A576E5A7234753778214125432A462D4A614E645267556B5870327335763879';        


            $query = DB::table('temp_supplier')->updateOrInsert(    [   'supplier_id'           => $supplier_id ],
                                                                    [
                                                                        'supplier_id'           => $supplier_id,
                                                                        'supplier_name'         => $supplier_name, 
                                                                        'supplier_group_id'     => $supplier_group_id,
                                                                        'address'               => $address,
                                                                        'email'                 => $email,
                                                                        'contact'               => $contact,
                                                                        'business_permit'       => $business_permit,
                                                                        'owner_first_name'      => $fname,
                                                                        'owner_middle_name'     => $mname,
                                                                        'owner_last_name'       => $lname,
                                                                        'owner_ext_name'        => $ename,
                                                                        'owner_phone'           => $phone_no,
                                                                        'geo_code'              => $g->geo_code,
                                                                        'reg'                   => $reg_code,
                                                                        'prv'                   => $prov_code,
                                                                        'mun'                   => $mun_code,
                                                                        'brgy'                  => $brgy_code,
                                                                        'bank_long_name'        => $bank_long_name,
                                                                        'bank_short_name'       => $bank_short_name,
                                                                        'bank_account_no'       => DB::raw("AES_ENCRYPT('".$acc_no."', '".$private_secret_key."')"),
                                                                        'bank_account_name'     => $acc_name,
                                                                        'verified'              => $isVerified,
                                                                        'supplier_type'         => $supplier_type,
                                                                        'update_status'         => $update_status
                                                                    ]
                                                                );

        }

        return $query;

    }

    public function head_supplier_profile_updating_notification($rfo_email_data, $rfo_fullname, $rfo_role_data, $supplier_name, $supplier_fullname, $email, $contact, $reg, $date_created, $supplier_type){
  
        $query = MailController::send_head_supplier_profile_updating_notification(
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

        return $query;
  
    }

    public function get_temp_supplier_tbl_query($supplier_id){
        $region = session()->get('region');

        $private_secret_key = session('private_secret_key');

        $query = DB::table('temp_supplier')
                        ->select("supplier_id", "supplier_name", "supplier_group_id", "address", "email", "contact",
                                "business_permit", "owner_first_name", "owner_middle_name", "owner_last_name", "owner_ext_name", "owner_phone", "geo_code",
                                "reg", "prv","mun", "brgy", "bank_long_name", "bank_short_name", DB::raw(" AES_DECRYPT(bank_account_no,'".$private_secret_key."') as bank_account_no"), "bank_account_name", "date_created", "supplier_type", "update_status")
                        ->when($region, function($query, $region) use($supplier_id){
                            if($region != 13){
                                $query->where('reg', '=', $region)->where('supplier_id', '=', $supplier_id)->where('update_status', '=', '1');
                            }
                            else{
                                $query->where('supplier_id', '=', $supplier_id)->where('update_status', '=', '1');
                            }
                        })
                        ->get();

        return $query;

    }

    public function update_temp_supplier_tbl_query($supplier_id, $update_status){

        $query = DB::table('temp_supplier')->where('supplier_id', '=', $supplier_id)->update(['update_status' => $update_status]);

        return $query;

    }

    public function update_supplier_profile_query($supplier_id, $supplier_name, $address, $reg, $prv, $mun, $brgy, $business_permit, $email, $contact_no, $owner_first_name, $owner_middle_name, $ownerlast_name, $owner_ext_name, $owner_phone_no, $geo_code, $bank_long_name, $bank_short_name, $bank_account_name, $bank_account_no, $isVerified, $supplier_type, $update_status){
  
        $query = DB::table('supplier')->updateOrInsert(
                                                            ['supplier_id' => $supplier_id],
                                                            [
                                                                'supplier_name'     => $supplier_name,
                                                                'address'           => $address,
                                                                'business_permit'   => $business_permit,
                                                                'email'             => $email,
                                                                'contact'           => $contact_no,
                                                                'owner_first_name'  => $owner_first_name,
                                                                'owner_middle_name' => ($owner_middle_name == "null" || $owner_middle_name == "N/A") ? "N/A" : $owner_middle_name,
                                                                'owner_last_name'   => $ownerlast_name,
                                                                'owner_ext_name'    => ($owner_ext_name == "null" || $owner_ext_name == "N/A") ? "N/A" : $owner_ext_name,
                                                                'owner_phone'       => $owner_phone_no,
                                                                'geo_code'          => $geo_code,
                                                                'reg'               => $reg,
                                                                'prv'               => $prv,
                                                                'mun'               => $mun,
                                                                'brgy'              => $brgy,
                                                                'bank_long_name'    => $bank_long_name,
                                                                'bank_short_name'   => $bank_short_name,
                                                                'bank_account_name' => $bank_account_name,
                                                                'bank_account_no'   => $bank_account_no,
                                                                'verified'          => $isVerified,
                                                                'supplier_type'     => $supplier_type,
                                                                'update_status'     => $update_status
                                                            ]
                                                    );
  
        return $query;
  
    }
  
    public function update_user_first_update_supplier_profile_status_query($supplier_id, $supplier_profile_update_status){
  
        $query = DB::table('users')->where('user_id', $supplier_id)->update(
                                                                                ['first_access_supplier_profile_code' => $supplier_profile_update_status]
                                                                            );
  
        return $query;
  
    }
  
    public function approved_update_head_supplier_profile_notification($email, $fullname, $role, $username, $date_created){
  
        $query = MailController::send_approved_update_head_supplier_profile_notification(
                                                                                            $email, 
                                                                                            $fullname,
                                                                                            $role,
                                                                                            $username,
                                                                                            $date_created
                                                                                        );

        return $query;
  
    }

    // public function update_main_supplier_profile($supplier_id, $fname, $mname, $lname, $ename, $email, $contact_no, $address, $bank_name, $bank_acc_name, $bank_acc_no, $phone, $supplier_update_profile_status){

    //     $query = DB::table('supplier')->updateOrInsert(
    //                                                     ['supplier_id' => $supplier_id],
    //                                                     [
    //                                                         'supplier_id' => $supplier_id,
    //                                                         'owner_first_name' => $fname,
    //                                                         'owner_middle_name' => $mname,
    //                                                         'owner_last_name' => $lname,
    //                                                         'owner_ext_name' => $ename,
    //                                                         'email' => $email,
    //                                                         'contact' => $contact_no,
    //                                                         'address' => $address,
    //                                                         'bank_long_name' => $bank_long_name,
    //                                                         'bank_short_name' => $bank_short_name,
    //                                                         'bank_account_name' => $bank_acc_name,
    //                                                         'bank_account_no' => $bank_acc_no,
    //                                                         'owner_phone' => $phone,
    //                                                         'supplier_update_profile_status' => $supplier_update_profile_status,
    //                                                     ]
    //                                                 );

    //     return $query;

    // }

}
