<?php

namespace App\Imports;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use DB;

class TopupImport implements ToCollection,WithStartRow
{   
    protected $file_name;
    protected $result;


    public function __construct($file_name){   
        $this->file_name = $file_name;                    
    }



    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
        $result = [];
        $error = 0;

        try{
        
            foreach($collection as $key => $item){
                
                if(isset($item[0]) && isset($item[4])){
                    $rsbsa_number = trim($item[0]);
                    $account_number = trim($item[4]);
                    
                    $check_account = db::table("rffa2_farmers_served")
                                            ->where('RSBSARefNo',$rsbsa_number)
                                            ->where('PanaloKardNo',$account_number)
                                            ->where('is_uploaded',0)
                                            ->first();

                    // check account number and rsbsa number
                    if($check_account ){
                        
                        // check if not uploaded
                        if($check_account->is_uploaded == 0 ){

                        
                            $update_account = db::table('rffa2_farmers_served')
                                                ->where('RSBSARefNo',$check_account->RSBSARefNo)
                                                ->where('PanaloKardNo',$check_account->PanaloKardNo)
                                                ->update([
                                                    "file_name" => $this->file_name,
                                                    "is_uploaded" => 1,
                                                    "date_uploaded" => Carbon::now('GMT+8'),
                                                    "uploaded_by_id" => session('uuid'),
                                                ]);
                            
                            if(!$update_account){
                                $error++;
                            }
                        }
                    }else{
                        //  error if account did not found.


                    }
                }else{
                    // check if valid header

                }
            }

            

            $this->result = ["status"=>true,"message"=>'Successfully Uploaded.'];
          
        }catch(\Exception $e){

            $this->result = ["status"=>false,"message"=>$e->getMessage()];
        }
    }

    public function startRow():int
    {
        return 3;
    }

    public function result(){
        return $this->result;
    }
}
