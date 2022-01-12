<?php

namespace App\Modules\ProgramSrn\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ProgramSrnController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        if (!empty(session('uuid'))) {
            return view("ProgramSrn::index");
        }else{
            return redirect('/login');
        }
    }

    public function getSupplierProgramList(Request $request)
    {
        if ($request->ajax()) {
            $getData = DB::table('supplier_programs as sp')
                ->select(DB::raw("srn.srn,sp.supplier_id,sp.program_id,p.title,p.shortname,p.description,p.duration_start_date,p.duration_end_date,p.status"))
                ->leftJoin('programs as p', 'p.program_id', '=', 'sp.program_id')
                ->leftJoin('supplier_srn as srn', function ($join) {
                    $join->on('srn.supplier_id', '=', 'sp.supplier_id');
                    $join->on('srn.program_id', '=', 'sp.program_id');
                })
                ->where('sp.supplier_id',session('supplier_id'))
                ->get();               

                return Datatables::of($getData)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    if($row->status == "1"){
                        return '<h5><span class="label label-info">Active / Ongoing</span></h5>';
                    }else{
                        return '<h5><span class="label label-danger">Inactive / Ended</span></h5>';
                    }                   
                })
                ->rawColumns(['status'])
                ->make(true);
        }
    }

    
}
