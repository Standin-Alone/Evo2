<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use DB;
use Ramsey\Uuid\Uuid;
use Mail;
use Illuminate\Support\Str;
class UsersImport implements ToCollection,WithStartRow
{   

    protected $region;
    protected $program_id;
    public function __construct($region, $program_id){

        $this->region = $region;
        $this->program_id = $program_id;

	}
	

    /**
    * @param Collection $collection
    */

    public function collection(Collection $row)
    {       

        // First Name   = 0
        // Middle Name  = 1
        // Last Name    = 2
        // Role         = 3
        // Email        = 4
        // Province     = 5
        // Municipality = 6
        // Barangay     = 7
        

        

        
        foreach($row as $item){
            $first_name   = $item[0];
            $middle_name  = $item[1];
            $last_name    = $item[2];
            $ext_name     = $item[3];
            $role         = $item[4];
            $agency       = db::table('agency')->where('agency_shortname',$item[5])->first()->agency_id;
            $email        = $item[6];
            $contact      = $item[7];
            $province     =  db::table('geo_map')->where('prov_name',$item[8])->first()->prov_code;
            $municipality =  db::table('geo_map')->where('mun_name',$item[8])->first()->mun_code;
            $barangay     =  db::table('geo_map')->where('bgy_name',$item[8])->first()->bgy_code;


            $check_email = db::table('users')->where('email',$email)->get();
            $geo_code = db::table('geo_map')
                                ->where('reg_code',$this->region)
                                ->where('prov_code',$province)
                                ->where('mun_code',$municipality)   
                                ->where('bgy_code',$barangay)
                                ->first();

            if($check_email->isEmpty() 
                && $first_name   != '' 
                && $last_name    != ''
                && $role         != ''
                && $agency       != ''
                && $email        != ''
                && $contact      != ''
                && $province     != ''
                && $municipality != ''
                && $barangay     != ''
                ){
                $user_id        = Uuid::uuid4();
                $random_password = Str::random(4);


                $get_role = db::table('roles')->where('role',$role)->first();
                db::table('users')
                                ->insert([
                                    'user_id'  => $user_id,
                                    'agency'  => $agency,
                                    'agency_loc'  => 'RFO',
                                    'username'  => $email,
                                    'password'  => bcrypt($random_password),
                                    'email'  => $email,
                                    'geo_code'  => $geo_code->geo_code,
                                    'reg' =>$this->region,
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
                    "role_id" =>$get_role->role_id,
                    "program_id" => $this->program_id,
                    "user_id" => $user_id,                
                ]);


                Mail::send('UserManagement::user-account', ["username" => $email,"password" => $random_password,"role" => $get_role->role], function ($message) use ($email, $random_password) {
                    $message->to($email)->subject('User Account Credentials');                
                });


            }
            
        }
    }

    public function startRow():int
    {
        return 6;
    }
}
