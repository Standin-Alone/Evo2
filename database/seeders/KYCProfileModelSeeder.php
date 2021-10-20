<?php

namespace Database\Seeders;

use DB;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class KYCProfileModelSeeder extends Seeder
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

        // REGION IV A SEEDER START HERE

            // RIZAL
            // $barangay = ['Poblacion Itaas','Poblacion Ibaba', 'Kalayaan','Bagumbayan'];
            // $municipality = 'ANGONO';
            // $province = 'RIZAL';
            // $region = 'REGION IV-A CALABARZON';
            
            // QUEZON
            // $barangay = ['Dayap','Kanlurang Calutan', 'Silangang Maligaya','Poblacion II'];
            // $municipality = 'AGDANGAN';
            // $province = 'QUEZON';
            // $region = 'REGION IV-A CALABARZON';


            // LAGUNA
            // $barangay = ['San Agustin','San Gregorio', 'San Miguel','Bitin'];
            // $municipality = 'ALAMINOS';
            // $province = 'LAGUNA';
            // $region = 'REGION IV-A CALABARZON';


            // CAVITE
            $barangay = ['Luksuhin','Sikat', 'Upli','Palumlum'];
            $municipality = 'ALFONSO';
            $province = 'CAVITE';
            $region = 'REGION IV-A CALABARZON';    

        // REGION IV A SEEDER END HERE



        
        // REGION III SEEDER START HERE

            // BATAAN
            // $barangay = ['Bangkal','Calaylayan Pob.', 'Capitangan','Gabon'];
            // $municipality = 'ABUCAY';
            // $province = 'BATAAN';
            // $region = 'REGION III CENTRAL LUZON';
            
            // BULACAN
            // $barangay = ['Banaban','Baybay', 'Binagbag','Donacion'];
            // $municipality = 'ANGAT';
            // $province = 'BULACAN';
            // $region = 'REGION III CENTRAL LUZON';


            // NUEVA ECIJA
            // $barangay = ['Betes','Bibiclat', 'Bucot','La Purisima'];
            // $municipality = 'ALIAGA';
            // $province = 'NUEVA ECIJA';
            // $region = 'REGION III CENTRAL LUZON';


            // PAMPANGA
            // $barangay = ['Agapito del Rosario','Anunas', 'Balibago','Capaya'];
            // $municipality = 'ANGELES CITY';
            // $province = 'PAMPANGA';
            // $region = 'REGION III CENTRAL LUZON';    

            // TARLAC
            // $barangay = ['Baguindoc Baguinloc','Bantog', 'Campos','Carmen'];
            // $municipality = 'ANAO';
            // $province = 'TARLAC';
            // $region = 'REGION III CENTRAL LUZON';    

            // ZAMBALES
            $barangay = ['Bangan','Batonlapoc', 'Beneg','Capayawan'];
            $municipality = 'BOTOLAN';
            $province = 'ZAMBALES';
            $region = 'REGION III CENTRAL LUZON';    

            // AURORA
            // $barangay = ['Buhangin','Calabuanan', 'Obligacion','Pingit'];
            // $municipality = 'BALER Capital';
            // $province = 'AURORA';
            // $region = 'REGION III CENTRAL LUZON';   
        


        // REGION III SEEDER END HERE

        

        while( $i <= 100){     
            $random_id_number = mt_rand(0, 1);    
            $gov_id_type_number = mt_rand(0, 3);    
            $bgy_number = mt_rand(0, 3);    
            $uuid = Uuid::uuid4();
            $rsbsa_no = mt_rand(1000, 9999);
            $fund_id = "9fe1bbd0-ac56-41ff-abb5-6b662bd04084"; // region III
            // $fund_id = "711097a6-1440-4464-bd51-ddc856ae3154"; // region IV
            $dt = Carbon::create(2021, 10, 11, 0);
            
            $check_rsbsa = db::table('kyc_profiles')->where('rsbsa_no','04-56-27-041-'.$rsbsa_no)->get();

            if($check_rsbsa->isEmpty()){ 

                DB::table('kyc_profiles')->insert([
                    'kyc_id' => $uuid,
                    'rsbsa_no' => '04-56-27-041-'.$rsbsa_no,
                    'fintech_provider' => 'SPTI',                
                    'data_source' => 'FARMING',                
                    'first_name' => $faker->name,                
                    'last_name' => $faker->name,                
                    'id_number' => $id_number[$random_id_number],
                    'gov_id_type' => $gov_id_type[$gov_id_type_number],
                    'street_purok' => '',
                    'barangay' => $barangay[$bgy_number],
                    'bgy_code' => db::table('geo_map')->where('bgy_name',$barangay[$bgy_number])->first()->bgy_code,
                    'municipality' => $municipality,
                    'mun_code' => db::table('geo_map')->where('mun_name',$municipality)->first()->mun_code,
                    'district' => '',
                    'prov_code' => db::table('geo_map')->where('prov_name',$province)->first()->prov_code,
                    'province' => $province,
                    'reg_code' => db::table('geo_map')->where('reg_name',$region)->first()->reg_code,
                    'region' => $region,
                    'birthdate' => $faker->date,
                    'place_of_birth' => $province.','.$region,
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
                    'date_uploaded' => $dt->toDateTimeString(),
                    'fund_id' => $fund_id,               
                ]);

                $i++;
            }


            
        }
    }
}
