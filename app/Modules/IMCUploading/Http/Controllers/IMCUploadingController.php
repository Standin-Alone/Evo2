<?php

namespace App\Modules\IMCUploading\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use File;
use DB;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
class IMCUploadingController extends Controller
{

    public function __construct(){
        $this->middleware('session.module');
     
    }

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $action = session('check_module_path');
        session()->put('progress',0);
        session()->save();     
        $get_region = db::table('geo_map')->select(DB::raw('DISTINCT reg_code'),'reg_name')->get();
        return view("IMCUploading::imc-uploading",compact('get_region','action'));
        
    }
}
