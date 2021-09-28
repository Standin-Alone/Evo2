<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use DB;
use Faker\Generator as Faker;
class KYCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run( Faker $faker)
    {
        //
        $count = 500;
        $i = 0;
        $number = mt_rand(100000, 999999);
        
        $PRIVATE_KEY =  '3273357538782F413F4428472B4B6250655368566D5971337436773979244226452948404D635166546A576E5A7234753778214125442A462D4A614E64526755'.
                        '6A586E327235753778214125442A472D4B6150645367566B59703373367639792F423F4528482B4D6251655468576D5A7134743777217A25432646294A404E63'.
                        '5166546A576E5A7234753777217A25432A462D4A614E645267556B58703273357638792F413F4428472B4B6250655368566D597133743677397A244326452948'.
                        '2B4D6251655468576D5A7134743777397A24432646294A404E635266556A586E3272357538782F4125442A472D4B6150645367566B5970337336763979244226'.
                        '4428472B4B6250655368566D5971337436773979244226452948404D635166546A576E5A7234753778214125432A462D4A614E645267556B5870327335763879';
                        
        $uuid = Uuid::uuid4();
        
        $id_number = ['FOR UPDATING','BARANGAY VERIFIED'];
        $gov_id_type = ['VOTERS ID','DRIVERS LICENSE', 'SENIOR CITIZEN ID','OTHERS'];
        while( $i <= 50000){     
            $random_id_number = mt_rand(0, 1);    
            $gov_id_type_number = mt_rand(0, 3);    
            $uuid = Uuid::uuid4();
            $rsbsa_no = mt_rand(1000, 9999);


            DB::table('kyc_profiles')->insert([
                'kyc_id' => $uuid,
                'rsbsa_no' => '04-56-27-041-'.$rsbsa_no,
                'fintech_provider' => 'SPTI',                
                'data_source' => 'FARMING',                
                'first_name' => $faker->name,                
                'last_name' => $faker->name,                
                'id_number' => $id_number[$random_id_number],
                'gov_id_type' => $gov_id_type[$gov_id_type_number],
                'street_purok' => $faker->citySuffix,
                'barangay' => $faker->state,
                'municipality' => $faker->state,
                'district' => $faker->citySuffix,
                'prov_code' => '00',
                'province' => $faker->city,
                'reg_code' => '00',
                'region' => $faker->city,
                'birthdate' => $faker->date,
                'place_of_birth' => $faker->date,
                'mobile_no' => $faker->phoneNumber,
                'sex' => 'MALE',
                'nationality' => 'Filipino',
                'profession' => 'Farmers',
                'sourceoffunds' => 'Farming',
                'mothers_maiden_name' =>$faker->name,
                'no_parcel' =>1,
                'total_farm_area' => mt_rand(1,9),
                'account_number' =>  DB::raw("AES_ENCRYPT(".mt_rand(10000000000,99999999999).",'".$PRIVATE_KEY."')"),
                'remarks' => '',                
            ]);



            $i++;
        }
    }
}
