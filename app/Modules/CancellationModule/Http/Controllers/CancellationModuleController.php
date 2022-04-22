<?php

namespace App\Modules\CancellationModule\Http\Controllers;

use ZipArchive;
use App\Exports;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Exports\GeneratePayoutExcelFile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Filesystem\Filesystem;
use App\Modules\CancellationModule\Models\CancellationModule;

class CancellationModuleController extends Controller
{

    public function __construct(Request $request)
    {
        $this->query_CancellationModule = new CancellationModule();
        $this->middleware('session.module');
        $this->middleware('session.notifications');
     
    }

    public function index()
    {
        if (!empty(session('uuid'))) {
            return view("CancellationModule::index");
        }else{
            return redirect('/login');
        }
    }

    public function getCancellationModuleList(Request $request){
        return $this->query_CancellationModule->get_CancellationModuleList($request);
    }

    public function getCancellationModuleDetails(Request $request){
        return $this->query_CancellationModule->get_CancellationModuleDetails($request);
    }

    public function getGeneratedCancellationModule(Request $request){
        return $this->query_CancellationModule->get_GeneratedCancellationModule($request);
    }

    public function submitCancellationModuleDetails(Request $request){
        return $this->query_CancellationModule->submit_CancellationModuleDetails($request);
    }

    public function downloadCancellationModuleTexfile(Request $request){
        return $this->query_CancellationModule->download_CancellationModuleTexfile($request);
    }

    public function getGeneratedFileDetails(Request $request){
        return $this->query_CancellationModule->get_GeneratedFileDetails($request);
    }



      
}
