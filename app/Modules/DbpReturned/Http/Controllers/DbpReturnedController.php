<?php

namespace App\Modules\DbpReturned\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\DbpReturned\Models\DbpReturned;

class DbpReturnedController extends Controller
{

    public function __construct(Request $request){

        $this->dbp_returned_model = new DbpReturned;

    }

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("DbpReturned::welcome");
    }

    public function main_index(Request $request){

        if($request->ajax()){
            return DataTables::of($this->dbp_returned_model->get_dbp_return_query())
            // ->addColumn('fullname_column', function($row){
            //     return $row->last_name.' '.$row->first_name.' '.$row->middle_name.' '.$row->ext_name;
            // })
            ->addColumn('dbp_status', function($row){
                if( ($row->dbp_status == "Credited") && ($row->iscancelled == "0") || ($row->iscancelled == "null") || ($row->iscancelled == "") ){
                    $html = '<h4><span class="badge" style="background-color: rgba(57,218,138,.17); color: #39DA8A!important; font-size: 12px;">CREDITED</span></h4>';
                }
                if(($row->dbp_status == "Credited") && (($row->iscancelled == "1"))){
                    $html = '<h4><span class="badge"  style="background-color: rgba(255,91,92,.17); color: #FF5B5C!important; font-size: 12px;">CANCELLED</span></h4>';
                }

                return $html;
            })
            ->rawColumns(['dbp_status'])
            ->make(true);
        }
     
        return view("DbpReturned::index");

    }
}
