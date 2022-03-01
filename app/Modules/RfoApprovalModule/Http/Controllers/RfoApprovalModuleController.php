<?php

namespace App\Modules\RfoApprovalModule\Http\Controllers;

use Ramsey\Uuid\Uuid;
use setasign\Fpdi\Fpdi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PhpOffice\PhpWord\Settings;
use LaravelQRCode\QRCodeFactory;
use PhpOffice\PhpWord\IOFactory;
use LaravelQRCode\Facades\QRCode;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\RfoApprovalModule\Models\RfoApprovalModule;
use App\Modules\RfoApprovalModule\Models\HeadSupplierProfileModel;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RfoApprovalModuleController extends Controller
{
    // private $fpdf;

    public function __construct(Request $request){

        $this->rfo_approval_model = new RfoApprovalModule;

        $this->head_supplier_model = new HeadSupplierProfileModel;

    }

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("RfoApprovalModule::welcome");
    }

    public function main_page(){

        return view("RfoApprovalModule::index");
    }

    public function list_of_request_for_account_activation(Request $request){

        $checklists = $this->rfo_approval_model->get_checklist();

        $checklist_details = $this->rfo_approval_model->view_checked_requirements_on_account_activation_query();

        $users = $this->rfo_approval_model->get_user_query();

        $programs = $this->rfo_approval_model->get_program();

        if($request->ajax()){
            return DataTables::of($this->rfo_approval_model->get_user_query())
            ->addColumn('fullname_column', function($row){
                return $row->last_name.' '.$row->first_name.' '.$row->middle_name.' '.$row->ext_name;
            })
            ->addColumn('status', function($row){
                // Check if the approval status are [1]APPROVED, [2]FOR CHECKING
                if(($row->approval_status == 2)){
                    $html = '<h4><span class="badge" style="background-color: rgba(91, 176, 255, 0.17); color: #39acda!important; font-size: 12px;">FOR CHECKING</span></h4>';
                }
                if(($row->approval_status == 1)){
                    $html = '<h4><span class="badge" style="background-color: rgba(57,218,138,.17); color: #39DA8A!important; font-size: 12px;">APPROVED</span></h4>';
                }
                return $html;
            })
            ->addColumn('action', function($row){
                $html = '<a href="#" id="updateBtn" type="button" class="btn btn-xs btn-outline-info"
                            data-uuid= "'.$row->user_id.'"
                            data-fullname= "'.$row->first_name.' '.$row->middle_name.' '.$row->last_name.' '.$row->ext_name.'"
                            data-status= "'.$row->status.'"
                            data-approval_status= "'.$row->approval_status.'"
                            data-program_id= "'.$row->program_id.'"
                            data-program_title= "'.$row->title.'"
                            data-toggle="modal" data-target="#update_user_status_modal">
                            <i class="fa fa-cog"></i> Update Checklist
                        </a>';

                return $html;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
        }
     
        return view("RfoApprovalModule::account_activation", compact('checklists', 'checklist_details', 'users', 'programs'));

    }

    public function view_already_checked_requirements_on_account_activation(Request $request){

        $checklists = $this->rfo_approval_model->get_checklist();

        $checklist_details = $this->rfo_approval_model->view_checked_requirements_on_account_activation_query();

        return view("RfoApprovalModule::account_activation", compact('checklists', 'checklist_details'));

    }

    // public function selected_supplier_group(Request $request){

    //     dd($supplier_group_id = $request->supplier_group_id);

    //     $supplier_group_name = $request->supplier_group_name;

    //     dd($supplier_group_data = [$supplier_group_id, $supplier_group_name]);

    //     return response()->json($supplier_group_data);
    // }

    public function create_user_checklist_details(Request $request){

        // dd($request->all());

        $user_uuid = $request->user_id;

        $program_id = $request->program_id;

        $program_title = $request->program_title;

        $selected_list_id = $request->clistid;

        $checklist_status = $request->clistStatus;

        $user_status = $request->user_status;

        $acc_approval_status = $request->account_approval_status_code;
        // dd($acc_approval_status);

        $acc_status_name = $request->account_status_name;
        // dd($acc_status_name);

        // Generate token
        $first_access_supplier_profile_code = csrf_token();

        $date_created = Carbon::now('GMT+8')->toDateTimeString();

        // Get current Day
        $day = Carbon::now('GMT+8')->day;

        // Get current Month
        $month = Carbon::now('GMT+8')->format('F');

        // Session of RFO Approver
        $activated_by_fullname = session()->get('user_fullname');

        // Session of RFO Approver
        $checked_by_fullname = session()->get('user_fullname');

        // Session of RFO Approver
        $approved_by_fullname = session()->get('user_fullname');

        // Session of RFO Approver
        $reg_code = session()->get('region');
 
        // Update checklist details table
        $this->rfo_approval_model->save_user_account_requirement_checklist_query($selected_list_id, $user_uuid, $checked_by_fullname, $checklist_status);

        $first_login = '0';

        // Update users table 
        $this->rfo_approval_model->update_user_status_query($user_uuid, $user_status, $approved_by_fullname, $acc_approval_status, $first_access_supplier_profile_code, $first_login);

        // get checklist details table
        $checklist_details_tbl = $this->rfo_approval_model->view_checked_requirements_on_account_activation_query_v2($user_uuid);

        $get_list_checked_arr = [];
        $get_list_unchecked_arr = [];

        $get_checked_list = $this->rfo_approval_model->get_already_checked_in_checked_list($user_id);

        $get_unchecked_list = $this->rfo_approval_model->get_unchecked_in_checked_list($user_id);

        $get_checked_list = DB::table('checklist_details as cd')
                                        ->select('cd.list')
                                        ->where('cd.status', '=', "1")
                                        ->where('cd.user_id', '=', $user_uuid)
                                        ->orderBy('cd.sequence_no', 'asc')
                                        // ->orderBy('cd.user_id', 'asc')
                                        ->get();

        $get_unchecked_list = DB::table('checklist_details as cd')
                                        ->select('cd.list')
                                        ->where('cd.status', '=', "0")
                                        ->where('cd.user_id', '=', $user_uuid)
                                        ->orderBy('cd.sequence_no', 'asc')
                                        // ->orderBy('cd.user_id', 'asc')
                                        ->get();
        
        foreach($get_checked_list as $v1){
            array_push($get_list_checked_arr, $v1->list);
        }

        foreach($get_unchecked_list as $v2){
            array_push($get_list_unchecked_arr, $v2->list);
        }

        // get da_address table
        $da_adds = $this->rfo_approval_model->get_da_address_query($reg_code);

        $region_code = [];
        $office_address = [];
        $city = [];
        $province = [];

        foreach($da_adds as $v3){
            array_push($region_code, $v3->reg_no);
            array_push($office_address, $v3->address);
            array_push($city, $v3->city_municipality);
            array_push($province, $v3->province);
        }
    
        // get user
        $users = $this->rfo_approval_model->get_user_query_02($user_uuid, $program_id);

        foreach($users as $u){
            // get fullname
            $fullname = $u->first_name.' '.$u->last_name.' '.$u->ext_name;

            $srn_status = "1";

            // IF: The checklist are complete
            if($u->approval_status == "1"){
                $supplier_group_id = Uuid::uuid4();

                if($u->role_id == "6"){
                    // create new main branch                                                        
                    $this->rfo_approval_model->insert_new_main_branch_query($supplier_group_id, $u->company_name, $u->company_address, $fullname, $u->email, $approved_by_fullname);   
                    
                    // get new created main branch
                    $get_supplier_group_id = $this->rfo_approval_model->get_new_created_main_branch_query($supplier_group_id);   

                    $supplier_type = "main";

                    // create new supplier branch
                    foreach($get_supplier_group_id as $sg_id){
                        $this->rfo_approval_model->insert_new_supplier_main_branch_query($user_uuid, $u->company_name, $sg_id->supplier_group_id, $u->company_address, $u->email, $u->contact_no, $u->first_name, $u->middle_name, $u->last_name, $u->ext_name, $u->reg, $supplier_type);
                    }

                }
                else if($u->role_id == "7"){
                    // get main branch
                    // dd($get_supplier_group_id = $this->rfo_approval_model->get_new_created_main_branch_query($request->supplier_group_id)); 

                    $supplier_type = "branch";

                    // create new supplier branch
                    // foreach($get_supplier_group as $sg_id){
                        $this->rfo_approval_model->insert_new_supplier_branch_query($user_uuid, $u->company_name, $u->group_supplier_id, $u->company_address, $u->email, $u->contact_no, $u->first_name, $u->middle_name, $u->last_name, $u->ext_name, $u->reg, $supplier_type);
                    // }
                    // DB::table('supplier_srn')->insert([
                    //                                     'supplier_id'   => $user_uuid,
                    //                                     'program_id'    => $u->program_id,
                    //                                     'code_reg'      => $u->reg
                    //                                 ]);

                    // DB::unprepared("insert into supplier_srn (supplier_id, program_id, code_reg) values ('".$user_uuid."', '".$u->program_id."', '".$u->reg."')");
                }

                // Insert function SRN parameter: supplier_id, program_id.
                // DB::table('supplier_srn')->insert([
                //                                     'supplier_id'   => $u->user_id,
                //                                     'program_id'    => $u->program_id,
                //                                     'code_reg'      => $u->reg
                //                                  ]);

                // Get SRN 
                $srn = $this->rfo_approval_model->get_SRN_query($u->user_id);

                $srn_data = [];

                foreach($srn as $srn_val){

                    // $data = [];

                    $encrypted_srn = rtrim(strtr(base64_encode($srn_val->srn), '+/', '-_'), '=');

                    $encrypted_srn;

                    // QR Link when scan
                    array_push($srn_data, url("/verify-accreditation/".$encrypted_srn));

                    // array_push($srn_data, url("/".$srn_val->supplier_id."/".$srn_val->srn."/Verfied-Certificate"));

                }

                // Auto Generate Certificate of Accreditation and QR Code
                // Use FPDF
                $pdf = new Fpdi('L','mm','A4');

                $pdf->AddPage();

                $template_path = storage_path('template/Custom Accreditation Certificate 2022-converted.pdf');

                $pdf->setSourceFile($template_path);

                $tpl = $pdf->importPage(1);

                $pdf->useTemplate($tpl);

                // Regional Field Office
                $pdf->SetFont('Times', 'B', 20);
                $pdf->SetXY(114, 23);
                foreach($region_code as $reg){
                    $pdf->Cell(0, 10, $reg, 0, 0, 'L');
                }
               
                // Office Address
                $pdf->SetFont('Times', 'I', 13);
                $pdf->SetXY(51, 30);
                foreach($office_address as $oa){
                    $pdf->Cell(0, 10, $oa, 0, 0, 'L');
                }
                
                // Fullname - Company Name
                $pdf->SetFont('Times', 'B', 15);
                $pdf->SetXY(0, 80);
                $pdf->Cell(0, 10, $fullname.' - '.$u->company_name, 0, 0, 'C');

                // Company Address
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(0, 100);
                $pdf->Cell(0, 10, $u->company_address, 0, 0, 'C');

                // Day Issued 
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(80, 144);
                $pdf->Cell(0, 10, $day, 0, 0, '');

                // Month Issued
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(105, 144);
                $pdf->Cell(0, 10, $month, 0, 0, '');

                // Location Issued
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(70, 144);
                foreach($city as $c){
                    foreach($province as $p){
                        $pdf->Cell(0, 10, $c.",".$p, 0, 0, 'C');
                    }
                }

                $file_name = "generatedQRcode_".$u->user_id.".png";

                // /**
                //  *  URL: website_url/$u->user_id/
                // */
                // $qr_data = "user id: ".$u->user_id." | company name: ".$u->company_name;
                // $qr_data = url("/".$u->user_id."/SRN/Verfied-Certificate");

                foreach($srn_data as $srn_data_val){

                    $qr_code = QRCode::text($srn_data_val)
                                        ->setSize(4)
                                        ->setOutfile(public_path('/template/generated_qr/'.$file_name))
                                        ->png();

                }

                // UpdateOrInsert function to Database
                DB::table("qr_tbl")->updateOrInsert(
                                                ['qr_id' => $u->user_id],
                                                [
                                                    'qr_id' => $u->user_id, 
                                                    'qr_file_name' => $file_name,
                                                ]
                                           );
                    
                // Get QRCode Table
                $qr_tbl = DB::table("qr_tbl")->select("qr_id", "qr_file_name")->where("qr_id", "=", $u->user_id)->get();
                
                foreach( $qr_tbl as $qr_val){
                    // Get QR Image using Public folder
                    $pdf->Image(public_path("/template/generated_qr/".$qr_val->qr_file_name), 220, 152, 30, 30);
                }

                // Save the result
                $pdf->Output(storage_path('template/'.$fullname.'- Certificate of Accreditation.pdf'), "F");

                $attachmentPDF = $pdf->Output('ac.pdf', "S");

                //send mail for account activation
                $this->rfo_approval_model->account_activation_status_mail_v2(
                                                                                $user_uuid,
                                                                                // $srn,
                                                                                $first_access_supplier_profile_code,
                                                                                $u->company_name,
                                                                                $u->company_address,
                                                                                $u->email,
                                                                                $fullname,
                                                                                $u->contact_no,
                                                                                $u->shortname,
                                                                                $u->reg_shortname,
                                                                                $u->role,
                                                                                $acc_approval_status,
                                                                                $acc_status_name,
                                                                                $date_created,
                                                                                $get_list_checked_arr,
                                                                                $get_list_unchecked_arr,
                                                                                $checked_by_fullname,
                                                                                $approved_by_fullname,
                                                                                $attachmentPDF
                                                                            );            

                $success_response = ['success'=>true, 'message'=>'checklist are complete!', 'auth'=>false];
                return response()->json($success_response, 200);
            }
            // Else if: The checklist are incomplete
            else if($u->approval_status == "2"){
        
                $attachmentPDF = 0;

                // send mail for account activation
                $this->rfo_approval_model->account_activation_status_mail_v2(
                                                                                $user_uuid,
                                                                                // $srn,
                                                                                $first_access_supplier_profile_code,
                                                                                $u->company_name,
                                                                                $u->company_address,
                                                                                $u->email,
                                                                                $fullname,
                                                                                $u->contact_no,
                                                                                $u->shortname,
                                                                                $u->reg_shortname,
                                                                                $u->role,
                                                                                $acc_approval_status,
                                                                                $acc_status_name,
                                                                                $date_created,
                                                                                $get_list_checked_arr,
                                                                                $get_list_unchecked_arr,
                                                                                $checked_by_fullname,
                                                                                $activated_by_fullname,
                                                                                $attachmentPDF
                                                                            );

                $success_response = ['success'=>true, 'message'=>'checklist are not yet complete!', 'auth'=>false];
                return response()->json($success_response, 200);
            }
        }
    }

    // public function view_verify_cert(Request $request){

    //     $encrypted_srn = $request->srn;
    //     // $email = $email;

    //     $srn = rtrim(strtr(base64_decode($encrypted_srn), '+/', '-_'), '=');

    //     $srn = $this->rfo_approval_model->srn_verification_query($srn);

    //     foreach($srn as $srn_val){

    //         if($srn_val->status == "1"){

    //             return redirect()->route('verified_success_page');

    //         }
    //         if($srn_val->status == "0"){

    //             return redirect()->route('verified_page_not_found');

    //         }
    //         if($srn_val->status == "[]"){

    //             return redirect()->route('verified_page_not_found');

    //         }

    //     }

    // }

    // public function verified_cert_success(){
    //     return view("RfoApprovalModule::approved_update_page");
    // }

    // public function page_404_status(){
    //     return view("RfoApprovalModule::404_error");
    // }
    
    public function view_approved_checklists(Request $request){

        if($request->ajax()){
            return DataTables::of($this->rfo_approval_model->get_user_for_list_of_approved_checklist_query())
            ->addColumn('fullname_column', function($row){
                return $row->last_name.' '.$row->first_name.' '.$row->middle_name.' '.$row->ext_name;
            })
            ->addColumn('approval_status', function($row){
                // Check if the approva; status are [1]APPROVED, [2]FOR CHECKING
                if(($row->approval_status == 2)){
                    $html = '<h4><span class="badge" style="background-color: rgba(91, 176, 255, 0.17); color: #39acda!important; font-size: 12px;">FOR CHECKING</span></h4>';
                }
                if(($row->approval_status == 1)){
                    $html = '<h4><span class="badge" style="background-color: rgba(57,218,138,.17); color: #39DA8A!important; font-size: 12px;">APPROVED</span></h4>';
                }
                return $html;
            })
            ->rawColumns(['approval_status'])
            ->make(true);
        }
     
        return view("RfoApprovalModule::account_activation");

    }

    // View list of wala pang program permission
    public function setup_prgoram_for_users(Request $request){
        
        if($request->ajax()){
            return DataTables::of($this->rfo_approval_model->get_user_without_program_query())
            ->addColumn('fullname_column', function($row){
                return $row->last_name.' '.$row->first_name.' '.$row->middle_name.' '.$row->ext_name;
            })
            ->addColumn('action', function($row){
                $html = '<a href="#" id="addBtn_role_and_progs" type="button" class="btn btn-xs btn-outline-info"
                            data-uuid= "'.$row->user_id.'"
                            data-fullname= "'.$row->first_name.' '.$row->middle_name.' '.$row->last_name.' '.$row->ext_name.'"
                            data-status= "'.$row->status.'"
                            data-approval_status= "'.$row->approval_status.'"
                            data-toggle="modal" data-target="#add_role_and_program">
                            <i class="fa fa-cog"></i> Setup Role and Program
                        </a>';

                // $html = '<a href="#" id="" type="button" class="btn btn-xs btn-outline-info">
                //             <i class="fa fa-cog"></i> Setup Role and Program
                //         </a>';

                return $html;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view("RfoApprovalModule::account_activation");
    }

    public function create_setup_role_and_account(Request $request){

        $user_id = $request->user_id;
        $role_id = $request->selected_role;
        $program_id = $request->selected_program;
        $user_status = "2"; // Active
        $program_permission_status = "1"; // Active
        $approval_status = "2"; // For Checking

        // Insert program_id
        $this->rfo_approval_model->add_program_query($user_id, $program_id, $program_permission_status);

        // Update users table
        $this->rfo_approval_model->update_user_status_and_approval_status_query($user_id, $user_status, $approval_status);

        $success_response = ["success" => true, "message" => "Selected role and program have been added successfully!"];
        return response()->json($success_response, 200);

    }

    // View: Supplier branch approval page
    public function list_of_request_for_main_branch_approval(Request $request){

        if($request->ajax()){
            return DataTables::of($this->rfo_approval_model->get_supplier_group_query())
            ->addColumn('approval_status', function($row){
                if(($row->approval_status == 0)){
                    $html = '<h4><span class="badge" style="background-color: rgba(91, 176, 255, 0.17); color: #39acda!important;">BLOCK</span></h4>';
                }
                elseif(($row->approval_status == 1)){
                    $html = '<h4><span class="badge" style="background-color: rgba(57,218,138,.17); color: #39DA8A!important;">ACTIVE</span></h4>';
                }
                elseif(($row->approval_status == 2)){
                    $html = '<h4><span class="badge" style="background-color: rgba(255,91,92,.17); color: #FF5B5C !important;">INACTIVE</span></h4>';
                }
                return $html;
            })
            ->addColumn('action', function($row){
                $html = '<a href="#" id="update_approval_btn" type="button" class="btn btn-xs btn-outline-info"
                            data-supplier_group_id= "'.$row->supplier_group_id.'"
                            data-group_name="'.$row->group_name.'"
                            data-approval_status="'.$row->approval_status.'"
                            data-toggle="modal" data-target="#update_main_branch_approval_status_modal">
                            <i class="fa fa-cog"></i> Update Status
                        </a>';

                return $html;

            })
            ->rawColumns(['approval_status', 'action'])
            ->make(true);
        }

        return view("RfoApprovalModule::list_of_main_branch");

    }

    // View: list_of_supplier_and_merchants
    public function list_of_merchants_and_supplier(Request $request){

        $checklists = $this->rfo_approval_model->get_checklist();

        $checklist_details = $this->rfo_approval_model->view_checked_requirements_on_account_activation_query();

        if($request->ajax()){
            return DataTables::of($this->rfo_approval_model->get_supplier_list_query())
            ->addColumn('action', function($row){
                if($row->update_status == 0){
                    $html = '<button href="#" id="update_profile_btn" type="button" class="btn btn-xs btn-outline-info" disabled>
                                <i class="far fa-bell-slash"></i> Update Profile
                            </button>
                            &nbsp;&nbsp;&nbsp;
                            <a href="#" id="view_profile_btn" type="button" class="btn btn-xs btn-outline-success"
                                data-supplier_id= "'.$row->supplier_id.'"
                                data-role= "'.$row->role.'"
                                data-toggle="modal" data-target="#view_current_profile_modal">
                                <i class="fa fa-eye"></i> View Profile 
                            </a>';

                    return $html;
                }
                if($row->update_status == 1){

                    $html = '<a href="#" id="update_profile_btn" type="button" class="btn btn-xs btn-outline-info notification"
                                data-supplier_id= "'.$row->supplier_id.'"
                                data-role= "'.$row->role.'"
                                data-toggle="modal" data-target="#update_profile_modal">
                                <i class="fa fa-edit"></i> Update Profile 
                                <span class="badge">1</span>
                            </a>
                            &nbsp;&nbsp;&nbsp;
                            <a href="#" id="view_profile_btn" type="button" class="btn btn-xs btn-outline-success"
                                data-supplier_id= "'.$row->supplier_id.'"
                                data-role= "'.$row->role.'"
                                data-toggle="modal" data-target="#view_current_profile_modal">
                                <i class="fa fa-eye"></i> View Profile 
                            </a>';
                    
                    return $html;
                }
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view("RfoApprovalModule::list_of_supplier_and_merchants", compact('checklists', 'checklist_details'));

    }

    public function show_current_supplier_profile(Request $request){
        $supplier_id = $request->supplier_id;

        // Get temp_supplier table datas
        $current_supp = $this->rfo_approval_model->get_supplier_list_query_v2($supplier_id);

        foreach($current_supp as $cs){
            $reg_code = $cs->reg;
            $prov_code = $cs->prv;
            $mun_code = $cs->mun;
            $brgy_code  = $cs->brgy;

            $geo_map = $this->rfo_approval_model->get_supplier_geo_map($reg_code, $prov_code, $mun_code, $brgy_code);

            // dd($geo_map);
        }
        
        if($geo_map == '[]'){
            foreach($current_supp as $cs){
                $region_code = sprintf('%02d', $cs->reg);
                $geo_map = DB::select("SELECT reg_name FROM vmp_db.geo_map where reg_code = $region_code group by reg_code");

                $data = [$current_supp, $geo_map];
            }
            
        }else{
            $data = [$current_supp, $geo_map];
        }
       
        return response()->json($data);
    }

    // Display on update profile
    public function view_temp_supplier_profile(Request $request){

        $supplier_id = $request->supplier_id;

        // Get temp_supplier table datas
        $temp_supp = $this->rfo_approval_model->get_temp_supplier_tbl_query($supplier_id);

        foreach($temp_supp as $ts){
            $reg_code = $ts->reg;
            $prov_code = $ts->prv;
            $mun_code = $ts->mun;
            $brgy_code  = $ts->brgy;
            $geo_map = $this->rfo_approval_model->get_supplier_geo_map($reg_code, $prov_code, $mun_code, $brgy_code);
        }

        $datas = [$temp_supp, $geo_map];

        return response()->json($datas);
    }

    public function update_supplier_main_branch_approval_status(Request $request){

      $supplier_group_id = $request->supplier_group_id;
      $approval_status = $request->approval_status_code;
      $approved_by_fullname = $request->approved_by_fullname;

      $date_created = Carbon::now('GMT+8')->toDateTimeString();

      $supplier_group = $this->rfo_approval_model->get_supplier_group_query_02($supplier_group_id);

      $this->rfo_approval_model->update_main_branch_approval_status_query($supplier_group_id, $approval_status, $approved_by_fullname);

      foreach($supplier_group as $sg){

        $this->rfo_approval_model->supplier_approval_status_mail(
                                                                    $supplier_group_id,
                                                                    $sg->group_name,
                                                                    $sg->email,
                                                                    $sg->created_agency,
                                                                    $sg->created_by_fullname,
                                                                    $request->approval_status_name,
                                                                    $approval_status,
                                                                    $approved_by_fullname,
                                                                    $date_created
                                                                );

      }

      $success_response = ['success'=>true, 'message'=>'The supplier main branch have update successfully!', 'auth'=>false];
      return response()->json($success_response, 200);

    }
}
