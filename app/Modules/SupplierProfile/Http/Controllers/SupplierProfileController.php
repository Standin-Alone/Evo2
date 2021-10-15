<?php

namespace App\Modules\SupplierProfile\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\SupplierProfile\Models\SupplierProfile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
class SupplierProfileController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("SupplierProfile::index");
    }

    public function getSupplierList(Request $request)
    {
        
        if ($request->ajax()) {
            $data = DB::table('supplier')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" data-toggle="modal" data-target="#ViewModal" class="edit btn btn-success btn-sm"><span class="fa fa-edit"></span> Edit</a> 
                                  <a href="javascript:void(0)" data-toggle="modal" data-target="#Delete_Modal" class="delete btn btn-danger btn-sm"><span class="fa fa-trash-alt"></span> Delete</a>';
                    return $actionBtn;
                })
                ->addColumn('access_control', function($row){
                    $actionBtn = '<a href="javascript:void(0)" data-toggle="modal" data-target="#ViewDetails_Modal" class="edit btn btn-success btn-sm"><span class="fa fa-eye"></span> View Details</a> 
                                  <a href="javascript:void(0)" data-toggle="modal" data-target="#ProgramAccess_Modal" class="programaccess btn btn-danger btn-sm"><span class="fa fa-cogs"></span> Program Access</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action','access_control'])
                ->make(true);
        }
    }
}
