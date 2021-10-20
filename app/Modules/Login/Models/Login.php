<?php

namespace App\Modules\Login\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Modules\Login\Models\OTP;
use Illuminate\Support\Facades\DB;
use App\Modules\Login\Models\Login;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Login\Http\Controllers\MailController;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Login extends Model
{
    use HasFactory;

    protected $table = "users";

    // protected $primary_key = "user_id";

    public function get_module_permmission($role_id)
    {
        $params = [];

        $query = DB::table('sys_access_matrix as sam')
                        ->select('r.role_id', 'r.roles', 'sm.sys_module_id', 'sm.module', 'sp.sys_permission_id', 'sp.permission', 'sam.status')
                        ->leftJoin('sys_modules as sm', 'sam.sys_module_id', '=', 'sm.sys_module_id')
                        ->leftJoin('roles as r', 'sm.role_id', '=', 'r.role_id')
                        ->leftJoin('sys_permission as sp', 'sam.sys_permission_id', '=', 'sm.sys_permission_id')
                        ->where('sam.status', '=', '1')
                        ->where('r.role_id', '=', $role_id)
                        ->get();
        
        foreach($query as $row){
            $params[] = $row;
        }

        return ['params' => $params];        
    }

    // check user by email
    public function check_email($email){
        $query = Login::where('email', '=', $email)->exists();

        return $query;
    }

    // check user by uuid
    public function check_uuid($uuid){
        $query =  OTP::where("user_id", '=', $uuid)->exists();
        // $query = DB::table('user_otp')->where("user_id", '=', $uuid)->get();

        return $query;
    }

    // get user by email with role
    public function get_user($email){
        // $query = DB::select('select * from users where email = ?', [$email]);

        $query = DB::table('program_permissions as pp')
                        ->select('u.user_id', 'u.email', 'u.password', 'u.password_reset_status', 'u.username', 'u.first_name', 'u.last_name', 'u.ext_name', 'r.role', 'u.status')
                        ->leftJoin('roles as r', 'pp.role_id', '=', 'r.role_id')
                        ->leftJoin('users as u','pp.user_id', '=', 'u.user_id')
                        ->where('u.email', '=', $email)
                        ->groupBy('u.user_id', 'r.role')
                        // ->havingRaw('count(*)>1')
                        ->get();

        return $query;
    }

    // find user password by uuid with role
    public function find_user_password($uuid){
        // $query = DB::select('select user_id, email, username, password, password_reset_status from users where user_id = ?', [$uuid]);

        $query = DB::table('program_permissions as pp')
                        ->select('u.user_id', 'u.email', 'u.username', 'u.first_name', 'u.last_name', 'u.ext_name', 'u.password', 'u.password_reset_status', 'r.role')
                        ->leftJoin('roles as r', 'pp.role_id', '=', 'r.role_id')
                        ->leftJoin('users as u','pp.user_id', '=', 'u.user_id')
                        ->where('pp.user_id', '=', $uuid)
                        ->where('u.user_id', '=', $uuid)
                        ->groupBy('u.user_id', 'r.role')
                        // ->havingRaw('count(*)>1')
                        ->get();

        return $query;
    }

    // public function get_otp_query($uuid){
    //     $query = DB::table("user_otp")->where('user_id','=', $uuid)->get();

    //     return $query;
    // }

    public function reset_status_active($uuid, $exp_date){
        $query = DB::table('users')->where('user_id', $uuid)->update(['password_reset_status' => 1, 'password_expired_status' => $exp_date]);
        
        return $query;
    }

    public function get_user_id_and_password_status($uuid){
        // $query = DB::table('users')->select('user_id', 'password_reset_status')->where('user_id', '=', $uuid)->get();

        $query = DB::table('users')->select('user_id', 'password_reset_status', 'password_expired_status')->where('user_id', '=', $uuid)->get();

        return $query;
    }

    public function check_password_expiration($exp_date){
        $pass_start_date = $exp_date;
        $pass_end_date = 5; //mins
        $pass_expired_at = Carbon::parse($pass_start_date, 'GMT+8')->addMinutes($pass_end_date);

        // if date_created is 5mins expired
        if($pass_expired_at->lessThan(Carbon::now('GMT+8'))){
            return true;
        }
        // if not yet expired
        return false;
    }

    public function email_otp($uuid, $email, $username, $firstname, $lastname, $extname, $role_sets, $otp, $date_created){
        $query = MailController::send_OTP_mail($uuid, $email, $username, $firstname, $lastname, $extname, $role_sets, $otp, $date_created);

        return $query;
    }

    public function email_reset_link($uuid, $email, $username, $firstname, $lastname, $extname, $role_sets, $date_created){
        $query = MailController::send_reset_password($uuid, $email, $username, $firstname, $lastname, $extname, $role_sets, $date_created);

        return $query;
    }

    public function email_confirm_password($uuid, $email, $username, $firstname, $lastname, $extname, $role_sets, $date_created){
        $query = MailController::send_confirmation_update_password($uuid, $email, $username, $firstname, $lastname, $extname, $role_sets, $date_created);

        return $query;
    }

    public $timestamps = false;
}
