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
    private $inserted_count = 0;
    private $total_rows = 0;
    private $test_data = [];
    private $error_data = [];

    public function __construct($region, $program_id){

        $this->region = $region;
        $this->program_id = $program_id;

    }
    

    /**
    * @param Collection $collection
    */

    public function collection(Collection $row)
    {       


        

        try{


        $rows_inserted = 0;
        $region = $this->region;
        $program_id = $this->program_id;
        $error_data = [];


        foreach($row as $key => $item){
            $first_name   = $item[0];
            $middle_name  = $item[1];
            $last_name    = $item[2];
            $ext_name     = $item[3];
            $role         = $item[4];            
            $agency       = 1;
            $email        = $item[6];
            $contact      = $item[7];
            // $province     = $item[8];
            // $municipality = $item[9];
            // $barangay     = $item[10];
    
            $geo_map     =  db::table('geo_map')
                                // ->where('prov_name',$province)
                                // ->where('mun_name',$municipality)
                                // ->where('bgy_name',$barangay)
                                ->where('reg_code',$region)
                                ->first();



            $check_email = db::table('users')->where('email',$email)->get();

            
       
            if(
                 $check_email->isEmpty() 
                && (Str::contains(strtolower($email),'@gmail.com') || Str::contains(strtolower($email),'@yahoo.com') || Str::contains(strtolower($email),'@da.gov.ph'))                                                
                && $first_name   != '' 
                && $last_name    != ''
                && $role         != ''
                && $agency       != ''
                && $email        != ''
                && $contact      != ''
                && $geo_map
                 
                ){

                $user_id        = Uuid::uuid4();
                
                $random_password = Str::random(4);


                $get_role = db::table('roles')->where('role',$role)->first();

                db::transaction(function() use (&$error_data,&$rows_inserted,$user_id,$agency,$email,$random_password,$region,$program_id,$geo_map,$first_name,$middle_name,$last_name,$ext_name,$contact,$get_role){

                  $insert_user =   db::table('users')
                    ->insert([
                        'user_id'  => $user_id,
                        'agency'  => $agency,
                        'agency_loc'  => 'RFO',
                        'username'  => $email,
                        'password'  => bcrypt($random_password),
                        'email'  => $email,
                        'geo_code'  => $geo_map->geo_code,
                        'reg' =>$region,
                        // 'prov' =>$geo_map->prov_code,
                        // 'mun' =>$geo_map->mun_code,
                        // 'bgy' =>$geo_map->bgy_code,
                        'first_name' => $first_name,
                        'middle_name' => $middle_name,
                        'last_name' => $last_name,
                        'ext_name' => $ext_name,
                        'contact_no' => $contact,
                    ]);



                    $insert_program_permissions = db::table('program_permissions')->insert([
                        "role_id" =>$get_role->role_id,
                        "program_id" => $program_id,
                        "user_id" => $user_id,                
                    ]);

                    if($insert_program_permissions && $insert_user){
                        
                        Mail::send('UserManagement::user-account', ["username" => $email,"password" => $random_password,"role" => $get_role->role], function ($message) use ($email, $random_password) {
                            $message->to($email)->subject('User Account Credentials');                
                        });
                        // count row inserted
                        ++$rows_inserted;
                    }
                    
                });
                               
            }else{  

                            
                $error_remarks = '';
                // set error remarks
                if(!$check_email->isEmpty())
                {
                    $error_remarks = 'Email is already use.';
                }

                if(!(Str::contains(strtolower($email),'@gmail') || Str::contains(strtolower($email),'@yahoo.com') || Str::contains(strtolower($email),'@da.gov.ph')))
                {
                    
                    $error_remarks = ($error_remarks == ''  ? 'You can use gmail, yahoo or da.gov.ph email only.' : $error_remarks.','.'You can use gmail, yahoo or da.gov.ph email only.');
                }


             
                if($first_name == '' || $last_name == ''){
                    $error_remarks = ($error_remarks == ''  ? 'Incomplete Name' : $error_remarks.','.'Incomplete Name');
                }                

                if($agency == '' || $role == ''){
                    $error_remarks = ($error_remarks == ''  ? 'No role indicated or agency' : $error_remarks.','.'No role indicated or agency');
                }

                if($email == '' || $contact == ''){
                    $error_remarks = ($error_remarks == ''  ? 'No email or contact number' : $error_remarks.','.'No email or contact number');
                }

                if(!$geo_map){
                    $error_remarks = ($error_remarks == ''  ? 'Wrong address' : $error_remarks.','.'Wrong address');
                }

                $data = [
                    'first_name'          => $first_name,                    
                    'last_name'           => $last_name,
                    'email'               => $email ,
                    'contact'             => $contact ,
                    'agency'              => db::table('agency')->where('agency_id',$agency)->first()->agency_name,                    
                    // 'barangay'            => $barangay,
                    // 'municipality'        => $municipality,
                    // 'province'            => $province,                            
                    'region'              => db::table('geo_map')->where('reg_code',$region)->first()->reg_name,
                    'remarks'             => $error_remarks
                ];

                array_push($error_data,$data);            
            }
        }

        $this->inserted_count = $rows_inserted;   
        $this->total_rows = $row->count();
        $this->error_data = $error_data;
        $this->message = 'true';

    }catch(\Exception $e){

        // $this->test_data = $e->getMessage();
        $this->message = $e->getMessage();
    }
    }

    public function startRow():int
    {
        return 6;
    }

    public function getRowCount(){
        return json_encode(["total_rows" => $this->total_rows , "total_rows_inserted" => $this->inserted_count,"message"=>$this->message,"error_data" => $this->error_data]);
    }
}
