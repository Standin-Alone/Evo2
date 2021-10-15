<?php

namespace App\Modules\UserProfileModule\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public static function send_update_info($uuid, $email, $username, $firstname, $lastname, $extname, $contact, $role, $date_created){
        $update_info_data = [
            'uuid'                  => $uuid,
            'email'                 => $email,
            'username'              => $username,
            'firstname'             => $firstname,
            'lastname'              => $lastname,
            'extname'               => $extname,
            'contact'               => $contact,
            'role'                  => $role,
            'date_created'          => $date_created,
        ];

        Mail::send('UserProfileModule::mail.update_info_mail', ['user_data' => $update_info_data], function($message) use ($update_info_data) {
            $message->to($update_info_data['email'], $update_info_data['username'])
                    ->subject('User Profile | Info Update'); 
        });
    }

    public static function send_confirmation_update_password($uuid, $email, $username, $firstname, $lastname, $extname, $role, $date_created){
        $change_password_data = [
            'uuid'                  => $uuid,
            'email'                 => $email,
            'username'              => $username,
            'firstname'             => $firstname,
            'lastname'              => $lastname,
            'extname'               => $extname,
            'role'                  => $role,
            'date_created'          => $date_created,
        ];

        Mail::send('UserProfileModule::mail.change_password_mail', ['user_data' => $change_password_data], function($message) use ($change_password_data) {
            $message->to($change_password_data['email'], $change_password_data['username'])
                    ->subject('User Profile | Change Password'); 
        });
    }
}
