<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Faker\Generator as Faker;

class VoucherTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         //
         $count = 500;
         $i = 0;
         
         $number = mt_rand(100000, 999999);

                         
         $uuid = Uuid::uuid4();
         
        
         $region = ['03','04','05','06','12'];
         $get_vouchers   = db::table('voucher')->where('voucher_status','!=','FULLY CLAIMED')->whereIn('reg',$region)->get();
         $get_supplier_id = db::table('supplier')->whereIn('reg',$region)->get();
         $get_sub_programs = db::table('supplier_programs')->first();
         
         
          
         $document = ['Farmer with Commodity','Valid ID (front)','Valid ID (back)','Receipt'];

         
            


            foreach($get_vouchers as $item){
                $voucher_increment = 0;
                $voucher_attachments_increment = 0;
                $transaction_id = Uuid::uuid4();

                foreach($get_supplier_id as $item_supplier){


                db::table('voucher')
                            ->where('reference_no', $item->reference_no)
                            ->update(['voucher_status'=>'FULLY CLAIMED','amount_val'=>0]);
                            
                $check_supplier_program_id = db::table('supplier_programs')->where('supplier_id',$item_supplier->supplier_id)->first();
                            
                while($voucher_increment < 2){
                    $voucher_increment++;
                    $voucher_details_id = Uuid::uuid4();
                    
                    
                    if($check_supplier_program_id){

                 
                        db::table('voucher_transaction')->insert(
                            [
                                'voucher_details_id'  =>  $voucher_details_id,
                                'transaction_id'      =>  $transaction_id,
                                'reference_no'        =>  $item->reference_no,
                                'supplier_id'         =>  $item_supplier->supplier_id,
                                'sub_program_id'      =>  $check_supplier_program_id->sub_id,
                                'fund_id'             =>  $item->fund_id,
                                'quantity'            =>  1,                            
                                'item_category'       =>  '',
                                'total_amount'        =>  '5000'                                                  
                            ]
                        );
                    }
                }
            }

                while($voucher_attachments_increment < 4){
                
                    $attachment_id = Uuid::uuid4();
                     
                    $insert_receipt_and_farmer_with_commodity = db::table('voucher_attachments')->insert([
                        'attachment_id'      => $attachment_id,
                        'voucher_id'         => $item->voucher_id,
                        'transaction_id'     => $transaction_id,
                        'document'           => $document[$voucher_attachments_increment],
                        'file_name'          => 'f4b0df14-b973-4d0b-af09-deee8bc16777-08f50d21-2ae3-4db9-96ec-fcbc18e9b60b-Valid ID(back).jpeg',
                    ]);
                    $voucher_attachments_increment++;
                 }

                 
               

    

            //     $transaction_id = Uuid::uuid4();
            //     $attachment_id = Uuid::uuid4();

            //     $insert_valid_id = db::table('voucher_attachments')->insert([
            //         'attachment_id'      => $attachment_id,                    
            //         'transaction_id'     => $transaction_id,
            //         'document'           => $decode_valid_id->document,
            //         'file_name'          => $decode_valid_id->file_name,
            //     ]);





            // $transaction_id = Uuid::uuid4();


             
            //  $fund_id = "8cd5a0b9-ec2b-4930-b16c-2e3e8053b500"; 
            
             

 
             
         }
    }
}
