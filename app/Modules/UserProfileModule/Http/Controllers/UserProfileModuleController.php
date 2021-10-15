<?php

namespace App\Modules\UserProfileModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\UserProfileModule\Models\UserProfileModule;

class UserProfileModuleController extends Controller
{
    public function __construct(Request $request)
    {
        $this->UserProfileModel = new UserProfileModule;

        $this->middleware('session.module');
    }

    public function index(){
        $uuid = session()->get('uuid');

        if(request()->ajax()){
            return DataTables::of($this->UserProfileModel->show_user_details($uuid))->make(true);
        }
        return view("UserProfileModule::index");
    }

    public function user_information(Request $request){
        $email = $request->email;
        $contact = $request->contact;

        $date_created = Carbon::now('GMT+8')->toDateTimeString();

        $users = $this->UserProfileModel->get_user(session()->get('uuid'));

        $role_sets = [];

        foreach($users as $user_roles){
            $role = $user_roles->role;
            $role_sets[] = $role;
        }

        foreach($users as $user){
            if((!empty($email) == true) && (($contact == null) == true)){
                // Update Email only
                $this->UserProfileModel->update_email(session()->get('uuid'), $email);
    
                $date_created = Carbon::now('GMT+8')->toDateTimeString();
    
                $users = $this->UserProfileModel->get_user(session()->get('uuid'));
    
                $role_sets = [];
    
                foreach($users as $user_roles){
                    $role = $user_roles->role;
                    $role_sets[] = $role;
                }
    
                foreach($users as $user){
                    // send update on email and contact no. to email
                    $this->UserProfileModel->email_update_info(
                                                            $user->user_id, 
                                                            $user->email, 
                                                            $user->username, 
                                                            $user->first_name, 
                                                            $user->last_name,
                                                            $user->ext_name, 
                                                            $user->contact_no, 
                                                            $role_sets, 
                                                            $date_created
                                                        );
                }
    
                $success_response = ['success'=> true, 'message' => 'The new email info is have been save!', 'auth' => false];
                return response()->json($success_response, 200);
            }
            else if((!empty($contact) == true) && (($email == null) == true)){
                // send update on email and contact no. to email
                $this->UserProfileModel->email_update_info(
                                                            $user->user_id, 
                                                            $user->email, 
                                                            $user->username, 
                                                            $user->first_name, 
                                                            $user->last_name,
                                                            $user->ext_name, 
                                                            // $user->contact_no,
                                                            $contact, 
                                                            $role_sets,
                                                            $date_created
                                                        );

                $success_response = ['success'=> true, 'message' => 'The new contact info is have been save!', 'auth' => false];
                return response()->json($success_response, 200);
            }
            else if((!empty($email) == true) && (!empty($contact) == true)){
                $this->UserProfileModel->update_all_info(session()->get('uuid'), $email, $contact);
    
                $date_created = Carbon::now('GMT+8')->toDateTimeString();
    
                $users = $this->UserProfileModel->get_user(session()->get('uuid'));
    
                $role_sets = [];
    
                foreach($users as $user_roles){
                    $role = $user_roles->role;
                    $role_sets[] = $role;
                }
    
                foreach($users as $user){
                    // send update on email and contact no. to email
                    $this->UserProfileModel->email_update_info(
                                                            $user->user_id, 
                                                            $user->email, 
                                                            $user->username, 
                                                            $user->first_name, 
                                                            $user->last_name,
                                                            $user->ext_name, 
                                                            $user->contact_no, 
                                                            $role_sets,
                                                            $date_created
                                                        );
                }
    
                $success_response = ['success'=> true, 'message' => 'The new info is have been save!', 'auth' => false];
                return response()->json($success_response, 200);
            }
            else{
                $error_response = ['error'=> true, 'message'=>"All input field are empty!", 'auth'=>false];
                return response()->json($error_response, 302);
            }
        }
    }

    public function password(Request $request){
        $current_password = $request->current_password;
        $new_password = $request->new_password;
        $confirm_password = $request->confirm_password;

        $users_uuid = $this->UserProfileModel->find_user_password(session()->get('uuid'));

        $role_sets = [];

        foreach($users_uuid as $user_roles){
            $role = $user_roles->role;
            $role_sets[] = $role;
        }

        foreach($users_uuid as $user_uuid){
            if(Hash::check($current_password, $user_uuid->password)){
                // check if the new password and confirm password is equal
                if($new_password == $confirm_password){
                    DB::table('users')->where('user_id', $user_uuid->user_id)->update(['password' => bcrypt($confirm_password)]);

                    $date_created = Carbon::now('GMT+8')->toDateTimeString();
    
                    // send confirmation update password to email
                    $this->UserProfileModel->email_confirm_password(
                                                                $user_uuid->user_id, 
                                                                $user_uuid->email, 
                                                                $user_uuid->username,                                                         
                                                                $user_uuid->first_name, 
                                                                $user_uuid->last_name,
                                                                $user_uuid->ext_name, 
                                                                $role_sets,
                                                                $date_created
                                                            );
    
                    $success_response = ['success'=> true, 'message' => 'The new password is have been save!', 'auth' => false];
                    return response()->json($success_response, 200);
                }
                else{
                    $error_response = ['error'=> true, 'message'=>"The confirm password does not match!", 'auth'=>false];
                    return response()->json($error_response, 302);
                }
            }
            else{
                $error_response = ['error'=> true, 'message'=>"The current password is incorrect!", 'auth'=>false];
                return response()->json($error_response, 302);
            }
        }
    }
}
