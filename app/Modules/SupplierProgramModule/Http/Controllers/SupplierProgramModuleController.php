<?php

namespace App\Modules\SupplierProgramModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\SupplierProgramModule\Models\SupplierProgramModule;

class SupplierProgramModuleController extends Controller
{
    public function __construct(Request $request)
    {
        $this->supplier_program_model = new SupplierProgramModule;

        $this->middleware('session.module');
    }

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("SupplierProgramModule::welcome");
    }

    /**
     * View on index.blade.php
     */
    public function list_of_suppliers(Request $request){
        $programs = $this->supplier_program_model->get_programs();

        $program_items =  $this->supplier_program_model->get_program_items();

        $supplier_id = $this->supplier_program_model->getSupplierProgramList();

        if($request->ajax()){
            return DataTables::of($this->supplier_program_model->getSupplierProgramList())
            ->addColumn('action', function($row){
                $return = '<a href="'.url('/supplier-program-module/create-setup-program-and-program-items/'.$row->supplier_id).'" id="setupBtn" type="button" class="btn btn-xs btn-outline-info" data-supplier_id="'.$row->supplier_id.'" data-toggle="modal" data-target="#setup_modal">
                            <i class="fa fa-cog"></i> Setup
                        </a>';

                return $return;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view("SupplierProgramModule::index", compact('programs', 'program_items', 'supplier_id'));

    }

    /**
     *  File connected to this function: index.blade.php and js.blade.php
     *  Trigger:  $("form#setup_program").on('submit', function(){});
     *  Data: from ajax: send data:{    program_id_value:program_id_value,
     *                                  program_name_text:program_name_text,
     *                                  program_item_id_value:program_item_id_value,
     *                                  program_item_name_text:program_item_name_text }
     *
     *  Note: this function get request from Ajax Data.
     */
    public function preview_setup_program(Request $request){

        // $supplier_id = $request->supplier_id;

        $program_id = $request->program_id_value;

        $program_item_id = $request->program_item_id_value;

        $program = $request->program_name_text;

        $program_item = $request->program_item_name_text;

        $program_item_details = $this->supplier_program_model->get_program_items_details($program_item_id);

        foreach($program_item_details as $val){
            $datas = [$val , ["program_id"=>$program_id]];
        }

        $datas = [$program_item_details, [["program_id"=>$program_id]], [["program"=>$program]]];

        return response()->json($datas);

    }

    /**
     *  File connected to this function: index.blade.php and js.blade.php
     *  Trigger:  $("form#setup_program").on('submit', function(){});
     *  Data: from ajax: send data:{ supplier_id:supplier_id,  arr_prog_id:arr_prog_id, arr_prog_item:arr_prog_item}
     *  Note: this function get request from Ajax Data.
     */
    public function get_setup_program_and_program_items(Request $request){

        $supplier_id = $request->supplier_id;
        $program_id = $request->arr_prog_id;
        $program_item_id = $request->arr_prog_item;

        $datas = [$program_id, $program_item_id, $supplier_id];

        return response()->json($datas);

    }

    /**
     *  File connected to this function: index.blade.php and js.blade.php
     *  Trigger:  $("form#create_program").on('submit', function(){});
     *  Data: from ajax: send data:{ supplier_id:supplier_id, program_item:program_item, supplier_id:supplier_id}
     *  Note: this function get request from Ajax Data.
     */
    public function add_setup_program_and_program_item(Request $request){

        $supplier_id = $request->supplier_id;
        $program_id = $request->program_id;
        $item_id = $request->program_item;

        $pid_r1 = str_replace('[', '', str_replace(']','', json_encode($request->program_id)));
        $pid_r2 = str_replace('\"', "", str_replace('\"',"", $pid_r1));
        $program_id = str_replace('"', "", str_replace('"',"", $pid_r2));

        $this->supplier_program_model->insert_new_setup($program_id, $supplier_id, $item_id);

        $success_response = ["success" => true, "message" => "The new setup is successfully created!"];
        return response()->json($success_response, 200);

    }

    public function show_list_of_supplier_programs(Request $request){

        if($request->ajax()){
            return DataTables::of($this->supplier_program_model->supplier_program_overview_query())
            ->addColumn('active', function($row){
                if($row->active == 1){
                    return $html = '<h4><span class="badge" style="background-color: rgba(57,218,138,.17); color: #39DA8A!important;">ACTIVE</span></h4>';
                }
                elseif($row->active == 0){
                   return $html = '<h4><span class="badge" style="background-color: rgba(255,91,92,.17); color: #FF5B5C!important;">INACTIVE</span></h4>';
                }
            })
            ->rawColumns(['active'])
            ->make(true);
        }

        return view("SupplierProgramModule::index");

    }

    public function get_programs_and_program_items(Request $request){

        $supplier_id = $request->supplier_id;

        $programs = $this->supplier_program_model->get_programs();

        $program_items =  $this->supplier_program_model->get_program_items();

        return view("SupplierProgramModule::index", compact('programs', 'program_items', 'supplier_id'));

    }
}
