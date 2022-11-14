<?php

namespace App\Modules\ProgramItemCategoriesManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class ProgramItemCategoriesManagement extends Model
{
    public function set_program_sub_category(){
        $result = [];
        try{

            $program_id = request('program_id');
            $fertilizer_sub_category_id = request('fertilizer_sub_category_id');


            $store_program = db::table("program_items_sub_category")
                ->insert([
                    "fertilizer_sub_category_id"=>$fertilizer_sub_category_id,
                    "program_id" => $program_id
                ]);

            if($store_program){
                $result = [
                    "status" => true,
                    "message" => "Successfully added new program to sub category."
                ];
            }else{
                $result = [
                    "status" => false,
                    "message" => "Failed to  add new program to sub category."
                ];
            }
            
            
        }catch(\Exception $e){
            $result = [
                "status" => true,
                "message" => $e->getMessage()
            ];
        }
        
        return $result;
    }


    public function set_program_category(){
        $result = [];
        try{

            $program_id = request('program_id');
            $fertilizer_category_id = request('fertilizer_category_id');


            $store_program = db::table("program_items_category")
                ->insert([
                    "fertilizer_category_id"=>$fertilizer_category_id,
                    "program_id" => $program_id
                ]);

            if($store_program){
                $result = [
                    "status" => true,
                    "message" => "Successfully added new program to category."
                ];
            }else{
                $result = [
                    "status" => false,
                    "message" => "Failed to  add new program to category."
                ];
            }
            
            
        }catch(\Exception $e){
            $result = [
                "status" => true,
                "message" => $e->getMessage()
            ];
        }
        
        return $result;
    }

    public function remove_program_sub_category(){
        $result = [];
        try{

            
            $program_item_sub_category_id = request('program_item_sub_category_id');


            $remove_program_sub_category = db::table("program_items_sub_category")
                ->where('program_item_sub_category_id',$program_item_sub_category_id)
                ->delete();

            if($remove_program_sub_category){
                $result = [
                    "status" => true,
                    "message" => "Successfully removed sub category."
                ];
            }else{
                $result = [
                    "status" => false,
                    "message" => "Failed to removed sub category."
                ];
            }
            
            
        }catch(\Exception $e){
            $result = [
                "status" => true,
                "message" => $e->getMessage()
            ];
        }
        
        return $result;
    }

    public function remove_program_category(){
        $result = [];
        try{

            
            $program_item_category_id = request('program_item_category_id');


            $remove_program_category = db::table("program_items_category")
                ->where('program_item_category_id',$program_item_category_id)
                ->delete();

            if($remove_program_category){
                $result = [
                    "status" => true,
                    "message" => "Successfully removed  category."
                ];
            }else{
                $result = [
                    "status" => false,
                    "message" => "Failed to removed  category."
                ];
            }
            
            
        }catch(\Exception $e){
            $result = [
                "status" => true,
                "message" => $e->getMessage()
            ];
        }
        
        return $result;
    }

    public function add_category(){
        $result = [];
        try{

            
            $program = request('program');
            $category_name = request('category_name');


            $insert_fertilizer_category = db::table('fertilizer_category')
                ->insertGetId(["category" => $category_name]);
            
            

            if($insert_fertilizer_category){
                $fertilizer_category_id = $insert_fertilizer_category;
                $insert_fertilizer_program = db::table('program_items_category')
                    ->insert([
                        "fertilizer_category_id" => $fertilizer_category_id,
                        "program_id" => $program
                    ]);

                if($insert_fertilizer_program){
                    $result = [
                        "status" => true,
                        "message" => "Successfully added new  category."
                    ];
                }else{
                    $result = [
                        "status" => false,
                        "message" => "Failed to add new category."
                    ];
                }
                
            }else{
                $result = [
                    "status" => false,
                    "message" => "Failed to removed  category."
                ];
            }
            
            
        }catch(\Exception $e){
            $result = [
                "status" => true,
                "message" => $e->getMessage()
            ];
        }
        
        return $result;
    }
}
