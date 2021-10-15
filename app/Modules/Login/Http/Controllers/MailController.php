<?php

namespace App\Modules\Login\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Modules\Login\Mail\ResetPasswordMail;

class MailController extends Controller
{
    public static function send_reset_password($uuid, $email, $username, $firstname, $lastname, $extname, $role_sets, $date_created ){
        $reset_pwd_data = [
            'uuid'                  => $uuid,
            'email'                 => $email,
            'username'              => $username,
            'firstname'             => $firstname,
            'lastname'              => $lastname,
            'extname'               => $extname,
            'role'                  => $role_sets,
            'date_created'          => $date_created,
        ];
        
        Mail::send('Login::mail.reset_password_mail', ['user_data' => $reset_pwd_data], function($message) use ($reset_pwd_data) {
            $message->to($reset_pwd_data['email'], $reset_pwd_data['username'])
                    ->subject('Login | Reset Password'); 
        });
    }

    public static function send_confirmation_update_password($uuid, $email, $username,  $firstname, $lastname, $extname, $role_sets, $date_created ){
        $confirm_update_data = [
            'uuid'              => $uuid,
            'email'             => $email,
            'username'          => $username,
            'firstname'         => $firstname,
            'lastname'          => $lastname,
            'extname'           => $extname,
            'role'              => $role_sets,
            'date_created'      => $date_created,
        ];
        
        Mail::send('Login::mail.confirm_update_password_mail', ['user_data' => $confirm_update_data], function($message) use ($confirm_update_data) {
            $message->to($confirm_update_data['email'], $confirm_update_data['username'])
                    ->subject('Login | Confirmation of new password'); 
        });
    }

    public static function send_OTP_mail($uuid, $email, $username, $firstname, $lastname, $extname, $role_sets, $otp, $date_created){
        $otp_data = [
            'uuid'              => $uuid,
            'email'             => $email,
            'username'          => $username,
            'firstname'         => $firstname,
            'lastname'          => $lastname,
            'extname'           => $extname,
            'role'              => $role_sets,
            'otp'               => $otp,
            'date_created'      => $date_created,
        ];

        Mail::send('Login::mail.otp_mail', ['user_data' => $otp_data], function($message) use ($otp_data) {
            $message->to($otp_data['email'], $otp_data['username'])
                    ->subject('Login | OTP'); 
        });
    }
}
