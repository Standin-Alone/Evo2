<?php

namespace App\Modules\UserManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Ramsey\Uuid\Uuid;
use Mail;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Modules\UserManagement\Models\UserManagement;
use Yajra\DataTables\Facades\DataTables;
class UserManagementController extends Controller
{
    public function __construct(Request $request)
    {
        $this->UserManagementModel = new UserManagement;

        $this->middleware('session.module');
    }
    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $get_regions = db::table('geo_map')->select('reg_code','reg_name')->distinct()->get();
        $get_agency = db::table('agency')->get();
        $get_roles = db::table('roles')->where('rfo_use','0')->get();
        $get_programs = db::table('programs')->where('status',1)->get();
        

        
        return view("UserManagement::index",compact('get_regions','get_agency','get_roles','get_programs'));
    }


    public function show(){
        $get_users =  db::table('users as u')
                            ->select(
                                db::raw("CONCAT(first_name,' ',last_name) as full_name"),
                                'role',
                                'p.shortname',
                                'pp.id as id',
                                'u.user_id',
                                'u.status',
                                'email',
                                'contact_no',
                                'agency_shortname as agency',                                
                                'reg_name',
                                'prov_name',
                                'mun_name',
                                'bgy_name',

                                )
                            ->leftjoin('program_permissions as pp', 'u.user_id', 'pp.user_id')
                            ->leftjoin('programs as p', 'p.program_id' , 'pp.program_id')
                            ->join('roles as r', 'r.role_id' , 'pp.role_id')
                            // ->join('geo_map as gm', 'gm.geo_code' , 'u.geo_code')                                                                                    
                            ->Join('geo_map as g', 'u.reg', '=', 'g.reg_code')
                            ->Join('geo_region as gr', 'gr.code_reg', '=', 'g.reg_code')
                            ->join('agency as a', 'a.agency_id' , 'u.agency')
                            ->groupBy('user_id','reg_code')                            
                            ->get();

        return datatables($get_users)->toJson();
    }
    

    public function destroy($id){
        $status = request('status');
                
        db::table('users')
            ->where('user_id',$id)
            ->update(['status'=> $status == 1 ? '0' : '1']);        
    }

    public function block($id){
        $status = request('status');
        
        

        db::table('users')
            ->where('user_id',$id)
            ->update(['status'=> $status == 2 ? '1' : '2']);
        
    }

    public function email(){
        return view('UserManagement::user-account');
    }
    public function filter_province($region_code)
    {       
        $get_province = db::table('geo_map')
                            ->select('prov_code','prov_name')
                            ->where('reg_code',$region_code)
                            ->distinct()->get();
        return json_encode($get_province);
    }


    public function filter_municipality($province_code)
    {       
        $get_municipality = db::table('geo_map')
                            ->select('mun_code','mun_name')
                            ->where('prov_code',$province_code)
                            ->distinct()->get();
        return json_encode($get_municipality);
    }

    public function filter_barangay($region_code,$province_code,$municipality_code)
    {       
        $get_barangay = db::table('geo_map')
                            ->select('bgy_code','bgy_name')
                            ->where('reg_code',$region_code)
                            ->where('prov_code',$province_code)
                            ->where('mun_code',$municipality_code)
                            ->distinct()->get();
        return json_encode($get_barangay);
    }

    public function filter_role($agency_loc)
    {       
        $get_roles = db::table('roles')                          
                            ->where('rfo_use',($agency_loc == 'RFO' ? '1' : '0' ) )
                            ->get();
        return json_encode($get_roles);
    }


    public function checkEmail()
    {           
        $get_email = request('email');
        $check_email = db::table('users')->where('email',$get_email)->get();

        if($check_email->isEmpty()){
            
            return 'true';
        }else{
            return 'false';
        }
        
    }

    public function store(){
        
        try{
            $user_id        = Uuid::uuid4();
            $random_password = Str::random(4);
            $first_name     = request('first_name');
            $middle_name    = request('middle_name');
            $last_name      = request('last_name');
            $ext_name       = request('ext_name');
            $email          = request('email');
            $contact        = request('contact');        
            $agency_loc     = request('agency_loc');
            $role           = request('role');
            $agency         = request('agency');
            $program        = request('program');
            $region         = request('region');
            $province       = request('province');
            $municipality   = request('municipality');
            $barangay       = request('barangay');
            
            $geo_code = db::table('geo_map')
                            ->where('reg_code',$region)
                            ->where('prov_code',$province)
                            ->where('mun_code',$municipality)   
                            ->where('bgy_code',$barangay)
                            ->first();
            if($geo_code){
                db::table('users')
                                    ->insert([
                                        'user_id'  => $user_id,
                                        'agency'  => $agency,
                                        'agency_loc'  => $agency_loc,
                                        'username'  => $email,
                                        'password'  => bcrypt($random_password),
                                        'email'  => $email,
                                        'geo_code'  => $geo_code->geo_code,
                                        'reg' =>$region,
                                        'prov' =>$province,
                                        'mun' =>$municipality,
                                        'bgy' =>$barangay,
                                        'first_name' => $first_name,
                                        'middle_name' => $middle_name,
                                        'last_name' => $last_name,
                                        'ext_name' => $ext_name,
                                        'contact_no' => $contact,
                ]);
            
            
                db::table('program_permissions')->insert([
                    "role_id" => $role,
                    "program_id" =>  $program  ,
                    "user_id" => $user_id,                
                ]);
                
                $get_role = db::table('roles')->where('role_id',$role)->first()->role;
                Mail::send('UserManagement::user-account', ["username" => $email,"password" => $random_password,"role" => $get_role], function ($message) use ($email, $random_password) {
                    $message->to($email)->subject('User Account Credentials');                
                });

                return 'true';
            }else{
                return 'false';
            }


        }catch(\Exception $e){
            return $e;
        }
    }
    
    // update user info
    public function update(){
        try{
            $id = request('id');
            $email = request('email');   
            $contact = request('contact');
            $role = request('role');

            $old_email = db::table('users')
                            ->where('user_id',$id)->first()->email;
            $update_info = db::table('users')
                            ->where('user_id',$id)
                            ->update([
                                'email' => $email,
                                'contact_no' => $contact,
                            ]);

            if($update_info){

                Mail::send('UserManagement::update-email', ["username" => $email,"role" => $role,"old_email" => $old_email], function ($message) use ($email) {
                    $message->to($email)->subject('Updated User Account');                
                });

                return 'true';
            }else{
                return 'false';
            }
        }
        catch(\Exception $e){
            return $e;
        }
    }

    public function import_file(){
        $region = request('import_region');
        $program = request('import_program');
        $file = request()->file('file');

        $user_import = new UsersImport($region,$program);
        Excel::import($user_import, $file);
        

        return $user_import->getRowCount();       
    }




    public function add_user_role(Request $request){
        $UserManagementModel = new UserManagement;

        $user_id = $request->select_user;
        $role_id = $request->select_role;
        $program_id = $request->select_program;
        $status = 1;

        $UserManagementModel->add_new_user_role($user_id, $role_id, $program_id, $status);

        $success_response = ["success" => true, "message" => "The add new user role have been submit successfully!"];
        return response()->json($success_response, 200);
    }

    public function list_of_users(){
        $users = $this->UserManagementModel->get_user();

        $programs = $this->UserManagementModel->get_program();

        $roles = $this->UserManagementModel->get_role();

        $agency = $this->UserManagementModel->get_agency();
        
        $region = $this->UserManagementModel->get_region();

        $action = request()->get('action');

        if(request()->ajax()){
            return DataTables::of($this->UserManagementModel->get_program_permission())
            ->addColumn('fullname_column', function($row){
                return $row->last_name.' '.$row->first_name.' '.$row->middle_name.' '.$row->ext_name;
            })
            ->addColumn('action', function($row){
                $return = '<a href="#" id="btn_data" type="button" class="btn btn-success" data-id="'.$row->user_id.'" data-toggle="modal" data-target="#ViewModal">
                            <i class="fa fa-eye"></i> View
                        </a>';

                return $return;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        // return view("UserManagement::list-of-users", ['users' => $users, 'roles' => $roles, 'programs' => $programs, 'region' => $region, 'agency' => $agency, 'action' => $action]); 
        return view("UserManagement::list-of-users")->with('users', $users)->with('roles', $roles)->with('programs', $programs)->with('region', $region)->with('agency', $agency)->with('action', $action); 
    }

    public function user_details($uuid){        
        // $uuid = "1d78da67-deaf-4100-862e-9d43f0df6b23";

        if(request()->ajax()){
            return DataTables::of($this->UserManagementModel->show_user_details($uuid))->make(true);
        }
        return view("UserManagement::list-of-users"); 
    }

    
}   
