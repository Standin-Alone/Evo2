<?php

namespace App\Modules\Login\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Modules\Login\Models\OTP;
use Illuminate\Support\Facades\DB;
use App\Modules\Login\Models\Login;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Modules\Login\Http\Controllers\MailController;

class LoginController extends Controller
{
    public function __construct(Request $request)
    {
        $this->loginModel = new Login;

        $this->otpModel = new OTP;
    }

    /**
     * View: login_page.blade.php
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        /**
         * 1.) Check if the user session is active
         *     go to user session dashboard page.
         * 2.) else return to login page 
         */ 
        if(Session::has('email')){
            return redirect()->route("main.home");
        }
        return view("Login::login_page");
    }

    public function go_to_login(){
        return redirect()->route("main.page");
    }

    /**
     * Action: when user click "Sign me in"
     */
    public function login_action(Request $request){
        // get request from input form
        $email = $request->email;
        $password = $request->password;

        // check email if exists
        $check_email = $this->loginModel->check_email($email);

        // get user
        $users = $this->loginModel->get_user($email);

        $role_sets = [];

        if($check_email == true){
            // get user roles
            foreach($users as $user_roles){
                $role = $user_roles->role;
                $role_sets[] = $role;
            }
            foreach($users as $user){
                if (Hash::check($password, $user->password)) {
                    // check user_id if exists
                    $check_otp_user_records = $this->loginModel->check_uuid($user->user_id);

                    // get user otp queries
                    $users_otp = $this->otpModel->get_otp_query($user->user_id);

                    // Check if status is Active
                    if($user->status == 1){
                        /**
                         * check the user_otp table if there's a record of the given user_id
                         * 1.) if true proceed checking the otp date expiration.
                         * 2.) if there is no existing data proceed generating new otp.
                         */
                        if(($check_otp_user_records == true)){
                            // if(($check_otp_user_records->isEmpty())){
                                $check = new OTP;
        
                                foreach($users_otp as $uOTP){
                                    if($check->check_otp_expiration($uOTP->date_created) == true){
                                        $this->otpModel->update_otp_status_to_deactive($user->user_id);
                                        $generate_otp = $this->otpModel->generate_otp($user->user_id);
                                        $otp = $generate_otp['otp'];
                                        $date_created = $generate_otp['date_created'];
            
                                        // send otp to email
                                        $this->loginModel->email_otp(
                                                                $user->user_id, 
                                                                $email,
                                                                $user->username,
                                                                $user->first_name,
                                                                $user->last_name, 
                                                                $user->ext_name,
                                                                $role_sets,
                                                                $otp,
                                                                $date_created
                                                            );
                
                                        $otp_mail_success = ['success'=>true, 'message'=>'OTP has been send through your email: "'.$email.'"', 'uuid'=>$user->user_id, 'auth'=>false];
                                        return response()->json($otp_mail_success, 200);
                                    }
                                    else{
                                        if($uOTP->status == "1"){
                                            $this->otpModel->update_otp_status_to_deactive($user->user_id);
        
                                            $generate_otp = $this->otpModel->generate_otp($user->user_id);
                                            $otp = $generate_otp['otp'];
                                            $date_created = $generate_otp['date_created'];
                
                                            // send otp to email
                                            $this->loginModel->email_otp(
                                                                    $user->user_id, 
                                                                    $email,
                                                                    $user->username,
                                                                    $user->first_name,
                                                                    $user->last_name, 
                                                                    $user->ext_name,
                                                                    $role_sets,
                                                                    $otp,
                                                                    $date_created
                                                                );
                                                                
                                            $otp_mail_success = ['success'=>true, 'message'=>'OTP has been send through your email: '."\r\n".''."\r\n".' Please check your email: "'.$user->email.'" ', 'uuid'=>$user->user_id, 'auth'=>false];
                                            return response()->json($otp_mail_success, 200);
                                        }
                                        else{
                                            $otp_mail_success = ['success'=>true, 'message'=>'Your OTP is not yet used. '."\r\n".''."\r\n".' Please check your email: "'.$user->email.'" ', 'uuid'=>$user->user_id, 'auth'=>false];
                                            return response()->json($otp_mail_success, 200);
                                        }
                                    }
                                }
                            }
                            else{
                                $generate_otp = $this->otpModel->generate_otp($user->user_id);
                                $otp = $generate_otp['otp'];
                                $date_created = $generate_otp['date_created'];
        
                                // send otp to email
                                $this->loginModel->email_otp(
                                                        $user->user_id, 
                                                        $email,
                                                        $user->username,
                                                        $user->first_name,
                                                        $user->last_name, 
                                                        $user->ext_name,
                                                        $role_sets,
                                                        $otp,
                                                        $date_created
                                                    );
            
                                $otp_mail_success = ['success'=>true, 'message'=>'OTP has been send through your email: "'.$email.'"', 'uuid'=>$user->user_id, 'auth'=>false];
                                return response()->json($otp_mail_success, 200);
                            }
                    // Check if status is InActive
                    }elseif($user->status == 0){
                        Auth::logout();
                        Session::flush();
                        $error_response = ['error'=> true, 'message'=>'Error! The account is <b>"INACTIVE"</b>. Please contact your administrator.', 'auth'=>false];
                        return response()->json($error_response, 302);
                    // Check if Status is Block
                    }elseif($user->status == 2){
                        Auth::logout();
                        Session::flush();
                        $error_response = ['error'=> true, 'message'=>'Error! The account is <b>"BLOCK"</b>. Please contact your administrator.', 'auth'=>false];
                        return response()->json($error_response, 302);
                    }
                }   
                else{
                    $error_response = ['error'=> true, 'message'=>"The email or password is incorrect!", 'auth'=>false];
                    return response()->json($error_response, 302);
                } 
            }
        }
        else{
            $error_response = ['error'=> true, 'message'=>"The email: '".$email."' doesn't exists!", 'auth'=>false];
            return response()->json($error_response, 302);
        } 

        return view("Login::login_otp");
    }

    /**
     * View: send_mail.blade.php
     */
    public function show_link_request_form(){
        return view("Login::password.send_email");
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
                    $success_response = ['success'=> true, 'message' => 'The reset password link is have been send to your email: "'.$email.'".', 'auth' => false];
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

    /**
     * View: change_password_page.blade.php
     */
    public function change_password_form($uuid){
        $this->loginModel = new Login;

        $users = $this->loginModel->get_user_id_and_password_status($uuid);

        foreach($users as $user){
            // $user->password_reset_status == 0
            if($this->loginModel->check_password_expiration($user->password_expired_status) == true){
                //update password reset status = '0'
                DB::table('users')->where('user_id', $uuid)->update(['password_reset_status' => 0]);
                // return redirect()->route("main.page");
                return view("Login::404_error");
            }elseif(($this->loginModel->check_password_expiration($user->password_expired_status) == false) && $user->password_reset_status == 1){
                return view("Login::password.change_password_page", ['user'=>$user]);
            }elseif($user->password_expired_status == null && $user->password_reset_status == 0){
                return view("Login::404_error");
            }
        }
    }

    public function update_password(Request $request, $uuid){
        // get request from input form

        // $old_pwd = $request->old_password;
        $new_pwd = $request->new_password;

        $this->loginModel = new Login;

        $users_uuid = $this->loginModel->find_user_password($uuid);
        
        $role_sets = [];
        foreach($users_uuid as $user_uuid){
            foreach($users_uuid as $user_roles){
                $role = $user_roles->role;
                $role_sets[] = $role;
            }
            // if(Hash::check($old_pwd, $user_uuid->password)){
                // update new user password and reset_password_link_status to "0"

                DB::table('users')->where('user_id', $uuid)->update(['password' => bcrypt($new_pwd), 'password_reset_status' => 0, 'password_expired_status' => NULL]);

                $uuid = $user_uuid->user_id;
                $email = $user_uuid->email;
                $username = $user_uuid->username;
                $firstname = $user_uuid->first_name;
                $lastname = $user_uuid->last_name;
                $extname = $user_uuid->ext_name;

                $date_created = Carbon::now('GMT+8')->toDateTimeString();

                // send confirmation update password to email
                $this->loginModel->email_confirm_password($uuid, $email, $username, $firstname, $lastname, $extname, $role_sets, $date_created);

                $success_response = ['success'=> true, 'message' => 'The new password is have been save!', 'auth' => false];
                return response()->json($success_response, 200);
            // }
            // else{
            //     $error_response = ['error'=> true, 'message'=>"The input old password does not match!", 'auth'=>false];
            //     return response()->json($error_response, 302);
            // }
        }
    }

    public function logout_action(){
        if(Session::has('uuid')){
            // $this->otpModel->update_otp_status_to_deactive(Session::get('uuid'));
            Session::flush();
        }

        return redirect()->route("main.page");
    }
}
