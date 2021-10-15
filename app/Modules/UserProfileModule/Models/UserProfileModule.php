<?php

namespace App\Modules\UserProfileModule\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\UserProfileModule\Http\Controllers\MailController;

class UserProfileModule extends Model
{
    use HasFactory;

    public function show_user_details($uuid){
        $query = DB::table('program_permissions as pp')
                        ->select('p.description', 'p.title', 'u.email', 'u.contact_no', 'r.role')
                        ->leftJoin('roles as r', 'pp.role_id', '=', 'r.role_id')
                        ->leftJoin('programs as p','pp.program_id', '=', 'p.program_id')
                        ->leftJoin('users as u','pp.user_id', '=', 'u.user_id')
                        ->where('u.user_id', '=', $uuid)
                        ->get();
        return $query;
    }

    // get user
    // public function get_user($uuid){
    //     $query = DB::table('users')
    //                     ->select('user_id', 'email', 'username', 'first_name', 'last_name', 'ext_name', 'contact_no')
    //                     ->where('user_id', '=', $uuid)
    //                     ->get();

    //     return $query;
    // }

    public function get_user($uuid){
        // $query = DB::select('select * from users where email = ?', [$email]);

        $query = DB::table('program_permissions as pp')
                        ->select('u.user_id', 'u.email', 'u.password', 'u.password_reset_status', 'u.username', 'u.first_name', 'u.last_name', 'u.ext_name', 'u.contact_no', 'r.role')
                        ->leftJoin('roles as r', 'pp.role_id', '=', 'r.role_id')
                        ->leftJoin('users as u','pp.user_id', '=', 'u.user_id')
                        ->where('u.user_id', '=', $uuid)
                        ->groupBy('u.user_id', 'r.role')
                        // ->havingRaw('count(*)>1')
                        ->get();

        return $query;
    }

    public function update_email($uuid, $email){
        $query = DB::update('update users set email = ? where user_id = ?', [$email, $uuid]);
        // $query = DB::table('users')
        //                 ->select('user_id', 'email')
        //                 ->where('user_id', $uuid,)
        //                 ->update(['email' => $email,]);
            
        return $query;
    }

    public function update_contact($uuid, $contact){
        $query = DB::update('update users set contact_no = ? where user_id = ?', [$contact, $uuid]);
        // $query = DB::table('users')
        //                 ->select('user_id', 'contact_no')
        //                 ->where('user_id', $uuid)
        //                 ->update(['contact_no'=> $contact]);
        
        return $query;
    }

    public function update_all_info($uuid, $email, $contact){
        $query = DB::update('update users set email = ?, contact_no = ? where user_id = ?', [$email, $contact, $uuid]);
        // $query = DB::table('users')
        //                 ->select('user_id', 'email', 'contact_no')
        //                 ->where('user_id', $uuid)
        //                 ->update(['email'       => $email, 
        //                           'contact_no'  => $contact
        //                 ]);

        return $query;
    }

    // find user password by uuid
    public function find_user_password($uuid){
        $query = DB::table('program_permissions as pp')
                        ->select('u.user_id', 'u.email', 'u.username', 'u.first_name', 'u.last_name', 'u.ext_name', 'u.password', 'u.password_reset_status', 'r.role')
                        ->leftJoin('roles as r', 'pp.role_id', '=', 'r.role_id')
                        ->leftJoin('users as u','pp.user_id', '=', 'u.user_id')
                        // ->where('pp.user_id', '=', $uuid)
                        ->where('u.user_id', '=', $uuid)
                        ->groupBy('u.user_id', 'r.role')
                        // ->havingRaw('count(*)>1')
                        ->get();

        return $query;
    }

    public function email_update_info($uuid, $email, $username, $firstname, $lastname, $extname, $contact, $role, $date_created){
        $query = MailController::send_update_info($uuid, $email, $username, $firstname, $lastname, $extname, $contact, $role, $date_created);

        return $query;
    }

    public function email_confirm_password($uuid, $email, $username, $firstname, $lastname, $extname, $role, $date_created){
        $query = MailController::send_confirmation_update_password($uuid, $email, $username, $firstname, $lastname, $extname, $role, $date_created);

        return $query;
    }

    public $timestamps = false;
}
