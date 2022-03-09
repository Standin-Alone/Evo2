<?php

namespace App\Modules\RfoApprovalModule\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\RfoApprovalModule\Http\Controllers\MailController;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class RfoApprovalModule extends Model
{
    use HasFactory;

    public function get_supplier_list_query()
    {

        $region = session()->get('region');

        $private_secret_key = session('private_secret_key');

        $decode_private_secret_key = "cast(AES_DECRYPT(s.bank_account_no,'".$private_secret_key."') as varchar(20))";

        $query = DB::table('supplier as s')
                        ->select(DB::raw("s.supplier_id, s.supplier_name, sg.group_name as supplier_group, s.address,
                            AES_DECRYPT(s.bank_account_no,'".$private_secret_key."') as bank_account_no,
                            s.email, s.contact, s.business_permit, s.owner_first_name, s.owner_middle_name, s.owner_last_name, s.owner_ext_name, s.owner_phone, s.geo_code,
                            s.reg, g.reg_name, s.prv, g.prov_name, s.mun, g.mun_name, s.brgy, g.bgy_name, s.bank_long_name, s.bank_short_name, s.bank_account_no, s.bank_account_name, s.update_status, r.role"))
                        ->leftJoin('supplier_group as sg', 'sg.supplier_group_id', 's.supplier_group_id')
                        ->leftJoin('geo_map as g','g.geo_code','s.geo_code')
                        ->leftJoin('program_permissions as pp', 'pp.user_id', 's.supplier_id')
                        ->leftJoin('roles as r', 'pp.role_id', '=', 'r.role_id')
                        ->when($region, function($query, $region){
                            if($region != 13){
                                $query->where('s.reg', '=', $region)->orderBy('s.supplier_name');
                            }
                            else{
                                $query->orderBy('s.supplier_name');
                            }
                        })
                        ->get();

        return $query;

    }

    public function get_supplier_list_query_v2($supplier_id)
    {

        $region = session()->get('region');

        $private_secret_key = session('private_secret_key');

        $decode_private_secret_key = "cast(AES_DECRYPT(s.bank_account_no,'".$private_secret_key."') as varchar(20))";

        $query = DB::table('supplier as s')
                        ->select(DB::raw("s.supplier_id, s.supplier_name, sg.group_name as supplier_group, s.address,
                            AES_DECRYPT(s.bank_account_no,'".$private_secret_key."') as bank_account_no,
                            s.email, s.contact, s.business_permit, s.owner_first_name, s.owner_middle_name, s.owner_last_name, s.owner_ext_name, s.owner_phone, s.geo_code,
                            s.reg, g.reg_name, s.prv, g.prov_name, s.mun, g.mun_name, s.brgy, g.bgy_name, s.bank_long_name, s.bank_short_name, s.bank_account_no, s.bank_account_name, s.update_status"))
                        ->leftJoin('supplier_group as sg', 'sg.supplier_group_id', 's.supplier_group_id')
                        ->leftJoin('geo_map as g','g.geo_code','s.geo_code')
                        ->when($region, function($query, $region) use($supplier_id){
                            if($region != 13){
                                $query->where('s.reg', '=', $region)->where('supplier_id', '=', $supplier_id);
                            }
                            else{
                                $query->where('supplier_id', '=', $supplier_id);
                            }
                        })
                        ->get();

        return $query;

    }

    public function get_temp_supplier_tbl_query($supplier_id){
        $region = session()->get('region');

        $private_secret_key = session('private_secret_key');

        // $val = 2135468;

        // $aes_encrypted = Crypt::encryptString($val);
        // dd($aes_encrypted);

        // $aes_decrypted = Crypt::decryptString($aes_encrypted);
        // dd($aes_decrypted);

        // $update_status = '1';

        $query = DB::table('temp_supplier')
                        ->select("supplier_id", "supplier_name", "supplier_group_id", "address", "email", "contact",
                                "business_permit", "owner_first_name", "owner_middle_name", "owner_last_name", "owner_ext_name", "owner_phone", "geo_code", "update_status",
                                "reg", "prv","mun", "brgy", "bank_long_name", "bank_short_name", DB::raw("AES_DECRYPT(bank_account_no,'".$private_secret_key."') as bank_account_no"), "bank_account_name", "date_created", "supplier_type")
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

    public function get_supplier_geo_map($reg_code, $prov_code, $mun_code, $brgy_code){

        $query = DB::table('geo_map')
                        ->select("geo_code", "reg_name", "prov_name", "mun_name", "bgy_name")
                        ->where('reg_code', '=', $reg_code)
                        ->where('prov_code', '=', $prov_code)
                        ->where('mun_code', '=', $mun_code)
                        ->where('bgy_code', '=', $brgy_code)
                        ->get();

        return $query;

    }

    public function get_user_without_program_query(){

        $region = session()->get('region');

        $role_id = ["22", "23", "6", "7"];

        $selected_program_id_on_dropdown = session()->get('Default_Program_Id');

        $query = DB::table('program_permissions as pp')
                        ->select('pp.program_id', 'p.title', 'p.shortname as program_shortname', 'u.user_id', 'u.company_name', 'u.company_address', 'u.email', 'u.username', 'u.first_name', 'u.middle_name', 'u.last_name', 'u.ext_name', 'r.role',
                                    'u.contact_no', 'u.reg', 'u.prov', 'gm.reg_name', 'gr.shortname', 'u.status', 'pp.status as permission_status', 'u.approval_status')
                        ->leftJoin('roles as r', 'pp.role_id', '=', 'r.role_id')
                        ->leftJoin('users as u','pp.user_id', '=', 'u.user_id')
                        ->leftJoin('programs as p', 'p.program_id', '=', 'pp.program_id')
                        ->leftJoin('geo_map as gm', 'gm.geo_code', '=', 'u.geo_code')
                        ->leftJoin('geo_region as gr', 'gr.code_reg' ,'=', 'u.geo_code')
                        ->when($region, function($query, $region) use($role_id){
                            if($region != 13){
                                $query->where('u.status', '=', '2')
                                      ->where('u.approval_status', '=', '0')
                                      ->where('u.reg', '=', $region)
                                    //   ->where('pp.program_id', '=', $selected_program_id_on_dropdown)
                                      ->where('pp.status', '=', '0')
                                      ->whereIn('pp.role_id', $role_id)
                                      ->groupBy('u.user_id')
                                      ->orderBy('u.date_created', 'DESC');
                            }
                            else{
                                $query->where('u.status', '=', '2')
                                      ->where('u.approval_status', '=', '0')
                                    //   ->where('pp.program_id', '=', $selected_program_id_on_dropdown)
                                      ->where('pp.status', '=', '0')
                                      ->whereIn('pp.role_id', $role_id)
                                      ->groupBy('u.user_id')
                                      ->orderBy('u.date_created', 'DESC');
                            }
                        })
                        ->get();


        return $query;

    }

    public function get_program(){

        $selected_program_id_on_dropdown = session()->get('Default_Program_Id');

        $query = DB::table('programs')
                        ->select('program_id', 'title', 'shortname', 'description')
                        ->where('program_id', '=', $selected_program_id_on_dropdown)
                        ->get();

        return $query;
    
    }

    // Add program id value on Program permission table
    public function add_program_query($user_id, $program_id, $program_permission_status){

        $query = DB::table('program_permissions')->where('user_id' , '=', $user_id)->update([
                                                                                                'program_id' => $program_id,
                                                                                                'status' => $program_permission_status,
                                                                                            ]);

        return $query;

    }

    // Update status and approval status column on Users Table
    public function update_user_status_and_approval_status_query($user_id, $user_status, $approval_status){

        $query = DB::table('users')->where('user_id', $user_id)->update([
                                                                            'status' => $user_status,
                                                                            'approval_status' => $approval_status
                                                                        ]);

        return $query;

    }

    public function get_user_query(){

        $region = session()->get('region');

        $query = DB::table('program_permissions as pp')
                            ->select('pp.program_id', 'p.title', 'p.shortname as program_shortname', 'u.user_id', 'u.company_name', 'u.company_address', 'u.email', 'u.username', 'u.first_name', 'u.middle_name', 'u.last_name', 'u.ext_name', 'r.role',
                                        'u.contact_no', 'u.reg', 'u.prov', 'gm.reg_name', 'gr.shortname', 'u.status', 'u.approval_status')
                            ->leftJoin('roles as r', 'pp.role_id', '=', 'r.role_id')
                            ->leftJoin('users as u','pp.user_id', '=', 'u.user_id')
                            ->leftJoin('programs as p', 'p.program_id', '=', 'pp.program_id')
                            ->leftJoin('geo_map as gm', 'gm.geo_code', '=', 'u.geo_code')
                            ->leftJoin('geo_region as gr', 'gr.code_reg' ,'=', 'u.geo_code')
                            ->when($region, function($query, $region){
                                if($region != 13){
                                    $query->where('u.status', '=', '2')->where('u.approval_status', '=', '2')->where('u.reg', '=', $region)->groupBy('u.user_id')->orderBy('u.date_created', 'desc');
                                }
                                else{
                                    $query->where('u.status', '=', '2')->where('u.approval_status', '=', '2')->groupBy('u.user_id')->orderBy('u.date_created', 'desc');
                                }
                            })
                            ->get();

        // $query = DB::table('users as u')
        //                 ->select('u.user_id', 'u.company_name', 'u.company_address', 'u.email', 'u.first_name', 'u.middle_name', 'u.last_name', 'u.ext_name', 'r.role',
        //                           'u.contact_no', 'u.reg', 'u.prov', 'u.status', 'u.approval_status')
        //                         //   , 'u.approved_by_fullname')
        //                 ->leftJoin('roles as r', 'pp.role_id', '=', 'r.role_id')
        //                 ->when($region, function($query, $region){
        //                     if($region != 13){
        //                         $query->where('s.reg', '=', $region)->orderBy('u.date_created', 'desc');
        //                     }
        //                     else{
        //                         $query->orderBy('u.date_created', 'desc');
        //                     }
        //                 })
        //                 ->get();

        return $query;

    }

    public function get_user_for_list_of_approved_checklist_query(){

        $region = session()->get('region');

        $query = DB::table('program_permissions as pp')
                            ->select('pp.program_id', 'p.title', 'p.shortname as program_shortname', 'u.user_id', 'u.company_name', 'u.company_address', 'u.email', 'u.username', 'u.first_name', 'u.middle_name', 'u.last_name', 'u.ext_name', 'r.role',
                                        'u.contact_no', 'u.reg', 'u.prov', 'gm.reg_name', 'gr.shortname', 'u.status', 'u.approval_status', 'u.approved_by_fullname')
                            ->leftJoin('roles as r', 'pp.role_id', '=', 'r.role_id')
                            ->leftJoin('users as u','pp.user_id', '=', 'u.user_id')
                            ->leftJoin('programs as p', 'p.program_id', '=', 'pp.program_id')
                            ->leftJoin('geo_map as gm', 'gm.geo_code', '=', 'u.geo_code')
                            ->leftJoin('geo_region as gr', 'gr.code_reg' ,'=', 'u.geo_code')
                            ->when($region, function($query, $region){
                                if($region != 13){
                                    $query->where('u.status', '=', '1')
                                          ->where('u.approval_status', '=', '1')
                                          ->where('u.reg', '=', $region)
                                          ->groupBy('u.user_id')
                                          ->orderBy('u.date_created', 'desc');
                                }
                                else{
                                    $query->where('u.status', '=', '1')
                                          ->where('u.approval_status', '=', '1')
                                          ->groupBy('u.user_id')
                                          ->orderBy('u.date_created', 'desc');
                                }
                            })
                            ->get();

        return $query;

    }

    public function get_supplier_group_query(){

        $region = session()->get('region');

        $query = DB::table('supplier_group as sg')
                        ->select('sg.supplier_group_id', 'sg.group_name', 'sg.address', 'sg.created_agency', 'sg.created_by_fullname'
                                  ,'sg.email', 'sg.approval_status', 'sg.approved_by_fullname')
                        ->when($region, function($query, $region){
                            if($region != 13){
                                $query->orderBy('sg.date_created', 'desc');
                            }
                            else{
                                $query->orderBy('sg.date_created', 'desc');
                            }
                        })
                        ->get();

        return $query;

    }

    public function get_checklist(){

        $region = session()->get('region');

        $query = DB::table('checklist as c')
                        ->select('c.checklist_id', 'c.list', 'c.sequence_no') 
                        ->orderBy('c.sequence_no', 'asc')
                        ->get();

        return $query;

    }

    public function get_already_checked_in_checked_list($user_uuid){

        $query = DB::table('checklist_details as cd')
                        ->select('cd.list')
                        ->where('cd.status', '=', "1")
                        ->where('cd.user_id', '=', $user_uuid)
                        ->orderBy('cd.sequence_no', 'asc')
                        // ->orderBy('cd.user_id', 'asc')
                        ->get();

        return $query;

    }

    public function get_unchecked_in_checked_list($user_uuid){

        $query = DB::table('checklist_details as cd')
                        ->select('cd.list')
                        ->where('cd.status', '=', "0")
                        ->where('cd.user_id', '=', $user_uuid)
                        ->orderBy('cd.sequence_no', 'asc')
                        // ->orderBy('cd.user_id', 'asc')
                        ->get();

        return $query;

    }

    public function get_user_query_02($user_id, $program_id){

        $region = session()->get('region');

        $query = DB::table('program_permissions as pp')
                            ->select('p.title', 'pp.program_id', 'u.user_id', 'u.company_name', 'u.company_address', 'u.email', 'u.username', 'u.first_name', 'u.middle_name', 'u.last_name', 'u.ext_name', 'r.role', 'r.role_id',
                                        'u.contact_no', 'u.reg', 'u.prov', 'gm.reg_name', 'gm.reg_shortname', 'gr.shortname', 'u.status', 'u.approval_status')
                            ->leftJoin('roles as r', 'pp.role_id', '=', 'r.role_id')
                            ->leftJoin('users as u','pp.user_id', '=', 'u.user_id')
                            ->leftJoin('programs as p', 'p.program_id', 'p.program_id', 'pp.program_id')
                            ->leftJoin('geo_map as gm', 'gm.geo_code', '=', 'u.geo_code')
                            ->leftJoin('geo_region as gr', 'gr.code_reg' ,'=', 'u.geo_code')
                            ->when($region, function($query, $region) use($user_id, $program_id){
                                if($region != 13){
                                    $query->where('pp.program_id', '=', $program_id)
                                          ->where('u.user_id', '=', $user_id)
                                          ->where('u.reg', '=', $region)
                                          ->groupBy('u.user_id')
                                          ->orderBy('u.date_created', 'desc');
                                }
                                else{
                                    $query->where('pp.program_id', '=', $program_id)
                                          ->where('u.user_id', '=', $user_id)
                                          ->groupBy('u.user_id')
                                          ->orderBy('u.date_created', 'desc');
                                }
                            })
                            ->get();

        return $query;

    }

    public function get_SRN_query($user_uuid){

        $query = DB::table('supplier_srn')
                        ->select('srn', 'supplier_id', 'program_id')
                        ->where('supplier_id', '=', $user_uuid)
                        ->get();

        return $query;

    }

    public function srn_verification_query($srn){

        $query = DB::table('supplier_srn')
                        ->select('status')
                        ->where('srn', '=', $srn)
                        // ->where('srn', '=', $srn)
                        ->get();

        return $query;

    }

    public function get_supplier_group_query_02($supplier_group_id){

        $region = session()->get('region');

        $query = DB::table('supplier_group as sg')
                        ->select('sg.supplier_group_id', 'sg.group_name', 'sg.address', 'sg.created_agency', 'sg.created_by_fullname'
                                  ,'sg.email', 'sg.approval_status', 'sg.approved_by_fullname')
                        ->when($region, function($query, $region) use($supplier_group_id){
                            if($region != 13){
                                $query->where('sg.supplier_group_id', '=', $supplier_group_id)
                                      ->orderBy('sg.date_created', 'desc');
                            }
                            else{
                                $query->orderBy('sg.date_created', 'desc');
                            }
                        })
                        ->get();

        return $query;

    }

    public function update_user_status_query($user_id, $status, $approved_by_fullname, $acc_approval_status, $first_access_supplier_profile_code, $first_login){

        // $query =  DB::table('users')->where('user_id', $user_id)->update(['status' => $status]);

        $query =  DB::table('users')->updateOrInsert(
                                                            ['user_id' => $user_id],
                                                            [
                                                                'user_id'                               => $user_id, 
                                                                'status'                                => $status, 
                                                                'approved_by_fullname'                  => $approved_by_fullname, 
                                                                'approval_status'                       => $acc_approval_status,
                                                                'first_access_supplier_profile_code'    => $first_access_supplier_profile_code,
                                                                'first_login'                           => $first_login
                                                            ]
                                                        );

        return $query;

    }

    public function view_checked_requirements_on_account_activation_query(){

        $query = DB::table('checklist_details as cd')
                                    ->select('cd.checklist_details_id', 'cd.checklist_id', 'cd.user_id', 'cd.list', 'cd.sequence_no', 'cd.status')
                                    // ->leftJoin('checklist as c', 'c.checklist_id', '=', 'cd.checklist_id')
                                    // ->whereIn('c.checklist_details_id', $selected_list_id)
                                    ->orderBy('cd.sequence_no', 'asc')
                                    ->orderBy('cd.user_id', 'asc')
                                    ->get();

        return $query;
        
    }

    public function view_checked_requirements_on_account_activation_query_v2($user_uuid){

        $query = DB::table('checklist_details as cd')
                                    ->select('cd.checklist_details_id', 'cd.checklist_id', 'cd.user_id', 'cd.list', 'cd.sequence_no', 'cd.status')
                                    // ->leftJoin('checklist as c', 'c.checklist_id', '=', 'cd.checklist_id')
                                    // ->whereIn('c.checklist_details_id', $selected_list_id)
                                    ->where('cd.user_id', '=', $user_uuid)
                                    ->orderBy('cd.sequence_no', 'asc')
                                    // ->orderBy('cd.user_id', 'asc')
                                    ->get();

        return $query;
        
    }

    public function save_user_account_requirement_checklist_query($selected_list_id, $user_uuid, $check_by_fullname, $checklist_status){

        $get_checklist = DB::table('checklist as c')
                                    ->select('c.checklist_id', 'c.list', 'c.sequence_no', 'c.status')
                                    ->whereIn('c.checklist_id', $selected_list_id)
                                    // ->whereIn('c.status', $checklist_status)
                                    ->orderBy('c.sequence_no', 'asc')
                                    ->get();

        $list = [];
        $seq = [];

        foreach($get_checklist as $clist){
            array_push($list, $clist->list);
            array_push($seq, $clist->sequence_no);
        }

        for($i=0; $i<count($selected_list_id); $i++){
            
            $query = DB::table('checklist_details')->updateOrInsert(
                                                                    [   
                                                                        'checklist_id' => $selected_list_id[$i], 
                                                                        'user_id' => $user_uuid, 
                                                                    ],
                                                                    [   
                                                                        'checklist_details_id' => Uuid::uuid4(), 
                                                                        'checklist_id' => $selected_list_id[$i], 
                                                                        'user_id' => $user_uuid, 
                                                                        'list' => $list[$i], 
                                                                        'sequence_no' => $seq[$i],
                                                                        'check_by_fullname' => $check_by_fullname,
                                                                        'status' => $checklist_status[$i],
                                                                    ]

            );
        }

        return $query;

    }

    public function get_supplier_group_tbl_query(){

        $query = DB::table('supplier_group')
                        ->select('supplier_group_id', 'group_name')
                        ->get();

        return $query;

    }

    public function insert_new_main_branch_query($supplier_group_id, $company_name, $company_address, $fullname, $email, $approved_by_fullname){

        // if($created_agency != null){
        //     $created_agency = "Bigasan Rice Baby";
        // }else{
        //     $created_agency = "N/A";
        // }

        $query = DB::table('supplier_group')->insert([
                                                        'supplier_group_id' => $supplier_group_id, 
                                                        'group_name' => $company_name, 
                                                        'address' => $company_address, 
                                                        'created_by_fullname' => $fullname,
                                                        'created_agency' => "N/A",
                                                        'email' => $email,
                                                        'approved_by_fullname' => $approved_by_fullname,
                                                        'approval_status' => "1"
                                                    ]);

        return $query;

    }

    public function get_new_created_main_branch_query($supplier_group_id){

        $query = DB::table('supplier_group')
                            ->select('supplier_group_id')
                            ->where('supplier_group_id', '=', $supplier_group_id)
                            ->get();

        return $query;
    
    }

    
    public function update_main_branch_approval_status_query($supplier_group_id, $approval_status, $approved_by_fullname){

        $query =  DB::table('supplier_group')->updateOrInsert(
                                                            ['supplier_group_id' => $supplier_group_id],
                                                            ['supplier_group_id' => $supplier_group_id, 'approval_status' => $approval_status, 'approved_by_fullname' => $approved_by_fullname]
                                                        );

        return $query;
    
    }

    public function get_da_address_query($reg_code){
        
        $query = DB::table("da_addresses")
                        ->select("reg_code", "reg_no", "da_shortname", "address", "city_municipality", "province")
                        ->where("reg_code", "=", $reg_code)
                        ->get();

        return $query;

    }
    public function insert_new_supplier_main_branch_query($user_id, $company_name, $get_supplier_group_id, $company_address, $email, $contact_no, $first_name, $middle_name, $last_name, $ext_name, $reg, $supplier_type){

        $query = DB::table('supplier')->insert([
                                                    'supplier_id' => $user_id, 
                                                    'supplier_name' => $company_name, 
                                                    'supplier_group_id' => $get_supplier_group_id, 
                                                    'address' => $company_address, 
                                                    'email' => $email,
                                                    'contact' => $contact_no,
                                                    'owner_first_name' => $first_name,
                                                    'owner_middle_name' => $middle_name,
                                                    'owner_last_name' => $last_name,
                                                    'owner_ext_name' => $ext_name,
                                                    // 'verified'  => "0",
                                                    'reg' => $reg,
                                                    'supplier_type' => $supplier_type,
                                                ]);

        return $query;
    }

    public function insert_new_supplier_branch_query($user_id, $company_name, $get_supplier_group_id, $company_address, $email, $contact_no, $first_name, $middle_name, $last_name, $ext_name, $reg, $supplier_type){

        $query = DB::table('supplier')->insert([
                                                    'supplier_id' => $user_id, 
                                                    'supplier_name' => $company_name, 
                                                    'supplier_group_id' => $get_supplier_group_id, 
                                                    'address' => $company_address, 
                                                    'email' => $email,
                                                    'contact' => $contact_no,
                                                    'owner_first_name' => $first_name,
                                                    'owner_middle_name' => $middle_name,
                                                    'owner_last_name' => $last_name,
                                                    'owner_ext_name' => $ext_name,
                                                    // 'verified'  => "0",
                                                    'reg' => $reg,
                                                    'supplier_type' => $supplier_type,
                                                ]);

        return $query;
    }


    public function account_activation_status_mail_v2($uuid, $first_access_supplier_profile_code, $company_name, $company_address, $email, $fullname, $contact_no, $reg_shortname, $shortname, $role, $acc_approval_status, $acc_status_name, $date_created, $get_list_checked_arr, $get_list_unchecked_arr, $checked_by_fullname, $activated_by_fullname, $attachmentPDF){

        $query = MailController::send_account_status_to_supplier_v2(
                                                                        $uuid, 
                                                                        // $srn,
                                                                        $first_access_supplier_profile_code,
                                                                        $company_name, 
                                                                        $company_address, 
                                                                        $email, 
                                                                        $fullname, 
                                                                        $contact_no, 
                                                                        $reg_shortname, 
                                                                        $shortname, 
                                                                        $role, 
                                                                        $acc_approval_status, 
                                                                        $acc_status_name, 
                                                                        $date_created, 
                                                                        $get_list_checked_arr, 
                                                                        $get_list_unchecked_arr, 
                                                                        $checked_by_fullname, 
                                                                        $activated_by_fullname,
                                                                        $attachmentPDF
                                                                    );

        return $query;

    }

    // public function account_activation_status_mail($uuid, $company_name, $company_address, $email, $fullname, $contact_no, $region, $role, $status_name, $status, $activated_by_fullname, $date_created){
    //     $query = MailController::send_account_status_to_supplier($uuid, $company_name, $company_address, $email, $fullname, $contact_no, $region, $role, $status_name, $status, $activated_by_fullname, $date_created);

    //     return $query;
    // }

    public function supplier_approval_status_mail($supplier_group_id, $group_name, $email, $created_agency, $created_by_fullname, $approval_status_name, $approval_status, $approved_by_fullname, $date_created){
        $query = MailController::send_main_branch_approval_to_merchant($supplier_group_id, $group_name, $email, $created_agency, $created_by_fullname, $approval_status_name, $approval_status, $approved_by_fullname, $date_created);

        return $query;
    }


}
