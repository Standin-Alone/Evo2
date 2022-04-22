<?php

namespace App\Modules\ProgramItemsModule\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramItemsModule extends Model
{
    use HasFactory;

    public function show_list_of_program_items_query(){

        $region = session()->get('region');  

        $query = DB::table('program_items as pi')
                        ->select('pi.item_id', 'pi.item_profile', 'pi.item_name', 'gr.region', 'pi.reg', 'pi.prv', 'pi.unit_measure', 'pi.ceiling_amount', 'pi.active', 'pi.date_created')
                        ->leftJoin('geo_region as gr', 'gr.code_reg', '=', 'pi.reg')
                        // ->where('pi.active', '=', "1")
                        // ->orderByDesc('pi.date_created')
                        ->when($region, function($query, $region){
                            if($region != 13){
                                $query->where('pi.reg', '=', $region)
                                      ->where('pi.active', '=', "1")
                                      ->orderByDesc('pi.date_created');
                            }
                            else{
                                $query->where('pi.active', '=', "1")
                                      ->orderByDesc('pi.date_created');
                            } 
                        })  
                        ->get();

        return $query;

    }

    public function get_reg(){

        $region = session()->get('region');
        
        if($region != 13){
            $region = session()->get('region');

            $query = DB::select('SELECT code_reg, region from geo_region where code_reg = '.$region.' GROUP BY region');
        }else{
            // $query = DB::select('SELECT code_reg, region from geo_region GROUP BY region');

            // Call the stored procedure "get_regions"
            $query = DB::select("CALL get_regions");
        }
        
        return $query;

    }

    public function get_prov($reg_code){

        // Call the stored procedure "get_provinces with parameter"
        $query = DB::select("CALL get_provinces(" . $reg_code . ")");

        return $query;

    }

    public function get_reg_for_update($region){

        $query = DB::table('geo_map')
                        ->select('reg_code', 'reg_name')
                        ->where('reg_code', '=', $region)
                        ->groupBy('reg_code')
                        ->get();
        
        return $query;

    }

    public function get_province_for_update($region, $province){

        $query = DB::table('geo_map')
                        ->select('prov_code', 'prov_name')
                        ->where('reg_code', '=', $region)
                        ->where('prov_code', '=', $province)
                        ->groupBy('prov_code')
                        ->get();
        
        return $query;

    }

    public function get_ProgramRegion(){
        $region = sprintf("%02d", session()->get('region'));

        $query = DB::table('geo_map')
                        ->select("reg_code", "reg_name")
                        ->when($region, function($query, $region){
                            if($region != 13){
                                $query->where('reg_code', '=', $region)
                                      ->groupBy('reg_name','reg_code');
                            }
                            else{
                                $query->groupBy('reg_name','reg_code');
                            } 
                        }) 
                        ->get();      

        return $query;
    }

    public function get_ProgramProvince($reg_code){
        // $region = sprintf("%02d", session()->get('region'));

        $query = DB::table('geo_map')
                        ->select("prov_code", "prov_name")
                        ->where('reg_code', '=', $reg_code)
                        ->groupBy('prov_name', 'prov_code')
                        ->get();

        return $query;
    }

    public function preview_region($region){

        $query = DB::select('SELECT reg_code, reg_name from geo_map where reg_code = '.$region.' GROUP BY reg_name HAVING COUNT(*) > 1');
        
        return $query;

    }


    public function preview_province($province){

        $query = DB::select('SELECT prov_code, prov_name FROM geo_map WHERE prov_code = '.$province.' GROUP BY prov_name HAVING COUNT(*) > 1');
        
        return $query;

    }

    public function insert_program_item_query($item_name, $image_name_with_extension, $unit_measure, $ceiling_amount, $region, $province){

        $query = DB::table('program_items')->insert([
            'item_id' => Uuid::uuid4(), 
            'item_name' => $item_name, 
            'item_profile' => $image_name_with_extension, 
            'reg' =>$region,
            'prv' =>$province, 
            'unit_measure' => $unit_measure, 
            'ceiling_amount' => $ceiling_amount
        ]);

        return $query;

    }

    public function selected_program_items_query($item_id){

        $region = session()->get('region');  

        $query = DB::table('program_items as pi')
                        ->select('pi.item_id', 'pi.item_profile', 'pi.item_name', 'gr.code_reg', 'gr.region', 'pi.reg', 'pi.prv', 'pi.unit_measure', 'pi.ceiling_amount', 'pi.active', 'pi.date_created')
                        ->leftJoin('geo_region as gr', 'gr.code_reg', '=', 'pi.reg')
                        // ->leftJoin('geo_map as gm', 'gm.reg_code', '=', 'pi.reg')
                        // ->where('pi.active', '=', "1")
                        // ->orderByDesc('pi.date_created')
                        ->when($region, function($query, $region) use($item_id){
                            if($region != 13){
                                $query->where('pi.reg', '=', $region)
                                      ->where('pi.active', '=', "1")
                                      ->where('pi.item_id', '=', $item_id)
                                      ->orderByDesc('pi.date_created');
                            }
                            else{
                                $query->where('pi.active', '=', "1")
                                      ->where('pi.item_id', '=', $item_id)
                                      ->orderByDesc('pi.date_created');
                            } 
                        })  
                        ->get();

        return $query;

    }

    public function update_program_item_query($program_item_id, $item_name, $image_name_with_extension, $unit_measure, $selectProgramRegion, $selectProgramProvince){

        $query =  DB::table('program_items')->where("item_id", "=", $program_item_id)->update([
                                                                                                'item_name' => $item_name,
                                                                                                'item_profile' => $image_name_with_extension,
                                                                                                'reg' => $selectProgramRegion,
                                                                                                'prv' => $selectProgramProvince,  
                                                                                                'unit_measure' => $unit_measure,
                                                                                            ]);

        return $query;

    }

    public function update_program_item_query_without_updating_img($program_item_id, $item_name, $unit_measure, $selectProgramRegion, $selectProgramProvince){

        $query =  DB::table('program_items')->where("item_id", "=", $program_item_id)->update([
                                                                                                'item_name' => $item_name,
                                                                                                'reg' => $selectProgramRegion,
                                                                                                'prv' => $selectProgramProvince,  
                                                                                                'unit_measure' => $unit_measure,
                                                                                            ]);

        return $query;

    }

    public function delete_program_item_query($item_id){

        $query = DB::table('program_items')->where('item_id', '=', $item_id)->delete();

        return $query;

    }
}
