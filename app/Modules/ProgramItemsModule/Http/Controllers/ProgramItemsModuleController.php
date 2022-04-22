<?php

namespace App\Modules\ProgramItemsModule\Http\Controllers;

// use file;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\ProgramItemsModule\Models\ProgramItemsModule;

class ProgramItemsModuleController extends Controller
{
    public function __construct(Request $request)
    {
        $this->program_items_module = new ProgramItemsModule;

        $this->middleware('session.module');
    }

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("ProgramItemsModule::welcome");
    }

    public function program_item_overview(Request $request){

        // Get region
        $regions = $this->program_items_module->get_ProgramRegion();

        // Get list of program items
        $program_items = $this->program_items_module->show_list_of_program_items_query();

        // #list-of-program-item-datatable
        if($request->ajax()){
            return DataTables::of($this->program_items_module->show_list_of_program_items_query())
            ->addColumn('item_imgs', function($row){
                
                $base_path = 'storage/commodities';

                $imgsrc = 'data:image/png;base64,'.base64_encode(file_get_contents($base_path.'/'.$row->item_profile)); 

                // dd($imgsrc);

                // onclick="rotationImgSens(image_item, 90, 1);
                $html ='<a id="image_item" href="' . $imgsrc .'" data-lightbox="'.$row->item_id.'" data-title="'.$row->item_name.'">
                        <img class="img-responsive " src="' . $imgsrc .'" alt="Product_Image" height="100px" width="100px">';
                
                return $html;
            })
            ->addColumn('active', function($row){
                if($row->active == 1){
                    $html = '<h4><span class="badge" style="background-color: rgba(57,218,138,.17); color: #39DA8A!important;">ACTIVE</span></h4>';
                } 
                elseif($row->active == 0){
                    $html = '<h4><span class="badge" style="background-color: rgba(255,91,92,.17); color: #FF5B5C!important;">INACTIVE</span></h4>';
                }
                return $html;
            })
            ->addColumn('action', function($row){
                $item_profile = 'data:image/png;base64,'.base64_encode(file_get_contents(('storage/commodities'.'/'.$row->item_profile))); 

                $html = '<a href="'.url('/program-items-module/index/view-program-item-modal/'.$row->item_id).'"
                            id="updateItemBtn" type="button" class="btn  btn-xs btn-block btn-outline-info" 
                            data-item_id= "'.$row->item_id.'" 
                            data-item_profile= "'.$item_profile.'"
                            data-toggle="modal" 
                            data-target="#update_program_item_modal">
                            <i class="fa fa-edit"></i> UPDATE ITEM
                        </a>
                        <a href="#" id="deleteItemBtn" type="button" class="btn btn-xs btn-block btn-outline-danger"
                        data-item_id= "'.$row->item_id.'"
                        <i class="fa fa-trash"></i> DELETE 
                        </a>';

                        // <a href="#" id="deleteItemBtn" type="button" class="btn btn-xs btn-block btn-outline-danger"
                        // data-item_id= "'.$row->item_id.'"
                        // data-toggle="modal" data-target="">
                        // <i class="fa fa-trash"></i> DELETE 
                        // </a>


                return $html;
            })
            ->rawColumns(['item_imgs', 'active', 'action'])
            ->make(true);
        }

        return view("ProgramItemsModule::index", compact('regions', 'program_items'));

    }

    public function view_program_item(Request $request){

        $item_id = $request->program_item_id;

        $item_profile = $request->item_profile;

        // Get program items query
        $selected_program_items = $this->program_items_module->selected_program_items_query($item_id);

        $region_code_data = [];
        $province_code_data = [];

        foreach($selected_program_items as $pi){
            array_push($region_code_data, $pi->reg);
            array_push($province_code_data, $pi->prv);
        }

        // Get region
        $region = $this->program_items_module->get_reg_for_update($region_code_data);

         // Get list 0f regions
        $list_of_regions = $this->program_items_module->get_ProgramRegion();

        // Get province
        $province = $this->program_items_module->get_province_for_update($region_code_data, $province_code_data);

        // Get list of provinces
        $list_of_provinces = $this->program_items_module->get_ProgramProvince($region_code_data,);

        $datas = [$selected_program_items, $region, $province, $list_of_regions, $list_of_provinces];

        return response()->json($datas);

        // return view("ProgramItemsModule::index", compact('selected_program_items', 'regions', 'item_profile'));
        
    }

    // public function get_regions(){
        
    //     // Get regions
    //     $regions = $this->program_items_module->get_ProgramRegion();

    //     // return to create_modal_js.blade.php as json format
    //     return response()->json($regions);

    // }

    public function get_province($reg_code){
        
        // Get provinces
        $provinces = $this->program_items_module->get_ProgramProvince($reg_code);

        // return to create_modal_js.blade.php as json format
        return response()->json($provinces);

    }

    public function create_program_items(Request $request){

        // Get the request item_profile
        $item_image = $request->file('item_profile');

        // Image get image_name and extension
        $image_name_with_extension = $item_image->getClientOriginalName();

        // convert image to base64
        $convert_to_b64 = base64_encode(file_get_contents($request->file('item_profile')));
 
        // store image to "storage > commodities(folder)"
        Storage::disk('commodities')->put($image_name_with_extension, base64_decode($convert_to_b64));

        // Image path
        // $get_image = base_path('storage/commodities/'.$image_name_with_extension);

        // Insert Query
        $this->program_items_module->insert_program_item_query(
                                                                $request->item_name, 
                                                                $image_name_with_extension, 
                                                                $request->unit_measure, 
                                                                $request->ceiling_amount, 
                                                                $request->selectProgramRegion, 
                                                                $request->selectProgramProvince
                                                            );
       
        $success_response = ["success" => true, "message" => "SAVED!"];
        return response()->json($success_response, 200);

    }

    public function update_program_item(Request $request){
        // dd($request->all());
        $image = $request->file('update_item_profile');

        if($image){
            
            // Get the request item_profile
            $image = $request->file('update_item_profile');
            
            // Image get image_name and extension
            $image_name_with_extension = $image->getClientOriginalName();

            // convert image to base64
            $convert_to_b64 = base64_encode(file_get_contents($request->file('update_item_profile')));
    
            // store image to "storage > commodities(folder)"
            Storage::disk('commodities')->put($image_name_with_extension, base64_decode($convert_to_b64));

            // dd("Item Profile: ".$image_name_with_extension." | Program ID: ".$request->update_item_id." | Item Name: ".$request->update_item_name." | unit_measure: ".$request->update_unit_measure." | Region: ".$request->update_selectProgramRegion." | Province: ".$request->update_selectProgramProvince);

            // Update Query
            $this->program_items_module->update_program_item_query(
                                                                    $request->update_item_id,
                                                                    $request->update_item_name,
                                                                    $image_name_with_extension, 
                                                                    $request->update_unit_measure, 
                                                                    $request->update_selectProgramRegion, 
                                                                    $request->update_selectProgramProvince
                                                                );
        }else{
            // Update Query
             $this->program_items_module->update_program_item_query_without_updating_img(
                                                                                            $request->update_item_id,
                                                                                            $request->update_item_name,
                                                                                            $request->update_unit_measure, 
                                                                                            $request->update_selectProgramRegion, 
                                                                                            $request->update_selectProgramProvince
                                                                                        );
        }



        $success_response = ["success" => true, "message" => "UPDATED SUCCESSFULL!"];
        return response()->json($success_response, 200);

    }

    public function delete_program_item(Request $request){

        // dd($request->all());

        $this->program_items_module->delete_program_item_query($request->item_id);

        $success_response = ["success" => true, "message" => "DELETED SUCCESSFULL!"];
        return response()->json($success_response, 200);

    }
}
