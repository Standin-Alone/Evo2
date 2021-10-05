<?php

namespace App\Modules\Login\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OTP extends Model
{
    use HasFactory;

    protected $table = 'user_otp';

    protected $fillable = ['otp_id', 'user_id', 'otp', 'date_created', 'status'];

    public function get_otp_query($uuid){
        $query = DB::table("user_otp")->where('user_id','=', $uuid)->get();

        return $query;
    }

    public function generate_otp($user_id){       
        // create random otp pin
        $otp = rand(1000, 9999);
        
        // default status "not active"
        $otp_status = "0";

        // create date and time base on GMT+8
        $otp_date_created = Carbon::now('GMT+8');

        // updateOrInsert() = update if the user has already exists and insert if not yet exists, create new data 
        DB::table('user_otp')->updateOrInsert(
            ['user_id'=>$user_id],
            ['user_id' => $user_id,'otp' => $otp, 'date_created' =>$otp_date_created, 'status' => $otp_status, ]
        );

        return ['otp'=>$otp, 'date_created'=>$otp_date_created];
    }
    
    public function check_otp_expiration($otp_date_created){
        $otp_start_date = $otp_date_created;
        $otp_end_date = 24; //mins
        $otp_expired_at = Carbon::parse($otp_start_date, 'GMT+8')->addHours($otp_end_date);

        // if date_created is 24hrs expired
        if($otp_expired_at->lessThan(Carbon::now('GMT+8'))){
            return true;
        }
        // if not yet expired
        return false;
    }

    public function update_otp_status_to_deactive($uuid){
        $query = DB::table('user_otp')->where('user_id', $uuid)->update(['status' => "0"]);
        return $query;
    }

    public function update_otp_status_to_active($uuid){
        $query = DB::table('user_otp')->where('user_id', '=', $uuid)->update(['status' => "1"]);
        return $query;
    }

    public function get_user_uuid($uuid){
        $query = DB::table('program_permissions as pp')
                        ->select('u.user_id', 'u.email', 'u.username', 'u.first_name', 'u.last_name', 'u.ext_name', 'u.password', 'u.password_reset_status', 'r.role')
                        ->leftJoin('roles as r', 'pp.role_id', '=', 'r.role_id')
                        ->leftJoin('users as u','pp.user_id', '=', 'u.user_id')
                        ->where('pp.user_id', '=', $uuid)
                        ->where('u.user_id', '=', $uuid)
                        ->groupBy('u.user_id', 'r.role')
                        ->get();

        return $query;
    }
}
