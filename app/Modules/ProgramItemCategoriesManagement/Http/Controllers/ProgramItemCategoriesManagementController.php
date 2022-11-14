<?php

namespace App\Modules\ProgramItemCategoriesManagement\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Modules\ProgramItemCategoriesManagement\Models\ProgramItemCategoriesManagement;

class ProgramItemCategoriesManagementController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   $get_programs = db::table("programs")            
            ->get();
        return view("ProgramItemCategoriesManagement::index",compact('get_programs'));
    }

    public function show(){
        try{
            $get_records = db::table("fertilizer_category as fc")                
                ->get();

            foreach($get_records as $item){
                // sub categories
                $get_sub_categories = db::table('fertilizer_sub_category')
                    ->where('fertilizer_category_id',$item->fertilizer_category_id)
                    ->get();
                    
                if(count($get_sub_categories) > 0){                    
                    $item->sub_categories = $get_sub_categories;                    
                }else{
                    $item->sub_categories = [];                    
                }

                // check program registered
                $get_program_categories = db::table('program_items_category as pic')
                    ->leftJoin('programs as p','p.program_id','pic.program_id')
                    ->where('fertilizer_category_id',$item->fertilizer_category_id)
                    ->get(); 
                
                if(count($get_program_categories)){
                    $item->program_categories = $get_program_categories;
                }else{
                    $item->program_categories = [];
                }

            }
        
            return datatables($get_records)->toJson();
        }catch(\Exception $e){

        }
    }

    public function show_registered_program_sub_category($fertilizer_category_id){
        try{
            $get_sub_categories = db::table('fertilizer_sub_category')
                ->where('fertilizer_category_id',$fertilizer_category_id)
                ->get();

             

            foreach($get_sub_categories as $item){
                $check_sub_category = db::table("program_items_sub_category as pisc")                
                    ->leftJoin('programs as p','p.program_id','pisc.program_id')
                    ->leftJoin('fertilizer_sub_category as fsc','fsc.fertilizer_sub_category_id','pisc.fertilizer_sub_category_id')
                    ->where('pisc.fertilizer_sub_category_id',$item->fertilizer_sub_category_id)
                    ->get();
            
                $item->programs = $check_sub_category;
                
                
            }                     
            return datatables($get_sub_categories)->toJson();
        }catch(\Exception $e){
            return $e->getMessage();
        }
        
    }

    public function filter_program_for_sub_category($fertilizer_sub_category_id){
        $result = [];
        try{
            
            $get_programs_registered = db::table('program_items_sub_category')
                ->where('fertilizer_sub_category_id',$fertilizer_sub_category_id)
                ->pluck('program_id');
            $get_programs = db::table("programs")
                ->whereNotIn('program_id',$get_programs_registered)
                ->get();

            if($get_programs){
                $result = [
                    "status" => true,
                    "data" => $get_programs
                ];
            }
            
            
        }catch(\Exception $e){
            $result = [
                "status" => true,
                "message" => $e->getMessage()
            ];
        }
        
        return response()->json($result);
    }
    public function filter_program_for_category($fertilizer_category_id){
        $result = [];
        try{
            
            $get_programs_registered = db::table('program_items_category')
                ->where('fertilizer_category_id',$fertilizer_category_id)
                ->pluck('program_id');
            $get_programs = db::table("programs")
                ->whereNotIn('program_id',$get_programs_registered)
                ->get();

            if($get_programs){
                $result = [
                    "status" => true,
                    "data" => $get_programs
                ];
            }
            
            
        }catch(\Exception $e){
            $result = [
                "status" => true,
                "message" => $e->getMessage()
            ];
        }

        return response()->json($result);
    }

    public function add_category(){
        $result = ProgramItemCategoriesManagement::add_category();
        return response()->json($result);
    }


    public function set_program_sub_category(){
        $result = ProgramItemCategoriesManagement::set_program_sub_category();
        return response()->json($result);
    }

    public function set_program_category(){
        $result = ProgramItemCategoriesManagement::set_program_category();
        return response()->json($result);
    }

    public function remove_program_sub_category(){
        $result = ProgramItemCategoriesManagement::remove_program_sub_category();
        return response()->json($result);
    }
    
    public function remove_program_category(){
        $result = ProgramItemCategoriesManagement::remove_program_category();
        return response()->json($result);
    }

   
}
