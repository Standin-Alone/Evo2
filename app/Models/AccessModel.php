<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Str;
class AccessModel extends Model
{

    public function change_password($user_id,$new_pass){
        $change_password = db::table('users')
            ->where('user_id',$user_id)
            ->update([
                'first_login' => '0',
                'password' => bcrypt($new_pass),                            
            ]);

        if($change_password){
            return 'true';
        }else{
            return 'false';
        }
    }

    public function check_default_password($user_id,$password){
        $check_password = db::table('users')->where('user_id',$user_id)->first();

        if(password_verify($password,$check_password->password)){
            return 'true';
        }else{
            return 'false';
        }
    }
}
