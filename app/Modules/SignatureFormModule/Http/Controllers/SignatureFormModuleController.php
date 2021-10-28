<?php

namespace App\Modules\SignatureFormModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class SignatureFormModuleController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("SignatureFormModule::welcome");
    }

    public function generate_signature_form_spti($dbp_batch_id,$dbp_batch_name){

        try{

            

            $get_profiles = db::table("kyc_profiles")->where('dbp_batch_id',$dbp_batch_id)->orderBy('last_name')->get();
            $count_profiles = count($get_profiles) ;
            $compute_page = ($count_profiles / 4);
            $total_page = ($compute_page %  2 ) == 0 ? $compute_page : $compute_page  +1;
            $my_template = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('SPTI-Signature-Card-Format.docx'));
            $my_template->cloneBlock('CLONEBLOCK', $total_page);                     
     
            $i = 0;
            
            // place
            while($i <= $count_profiles){
                if($i < $count_profiles){                    
                    $name_counter = $my_template->setValue('full_name','${full_name'.$i.'}',2);
                    $rsbsa_counter =$my_template->setValue('rsbsa_no','${rsbsa_no'.$i.'}',2);  
                }
                $i++;
            }
            
            foreach ($get_profiles as $key => $value) {                                
                echo $key.$value->first_name.' '.$value->last_name.'<br>';
                
                $my_template->setValue('full_name'.$key,$value->first_name.' '.$value->middle_name.' '.$value->last_name,2);
                $my_template->setValue('rsbsa_no'.$key,$value->rsbsa_no,2);
                $my_template->setValue('dbp_batch_name',$dbp_batch_name);                              
            }

            $my_template->setValue('full_name','');
            $my_template->setValue('rsbsa_no', '');  
   
            $my_template->saveAs('uploads/fintech-for-signature/SPTI/'.$dbp_batch_name.'-FOR-SIGNATURE.docx');

        }catch(\Exception $e){
            echo $e->getMessage();
        }

    }
}
