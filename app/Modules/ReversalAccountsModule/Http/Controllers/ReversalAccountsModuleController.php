<?php

namespace App\Modules\ReversalAccountsModule\Http\Controllers;

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
use App\Modules\ReversalAccountsModule\Models\ReversalAccountsModule;

class ReversalAccountsModuleController extends Controller
{

    public function __construct(Request $request)
    {
        $this->query_ReversalAccountsModule = new ReversalAccountsModule();
    }

    public function index()
    {
        if (!empty(session('uuid'))) {
            return view("ReversalAccountsModule::index");
        }else{
            return redirect('/login');
        }
    }

    public function getReversalAccountsModuleList(Request $request){
        return $this->query_ReversalAccountsModule->get_ReversalAccountsModuleList($request);
    }

    public function getReversalAccountsModuleDetails(Request $request){
        return $this->query_ReversalAccountsModule->get_ReversalAccountsModuleDetails($request);
    }

    public function getGeneratedReversalAccountsModule(Request $request){
        return $this->query_ReversalAccountsModule->get_GeneratedReversalAccountsModule($request);
    }

    public function submitReversalAccountsModuleDetails(Request $request){
        return $this->query_ReversalAccountsModule->submit_ReversalAccountsModuleDetails($request);
    }

    public function downloadReversalAccountsModuleTexfile(Request $request){
        return $this->query_ReversalAccountsModule->download_ReversalAccountsModuleTexfile($request);
    }

    public function getGeneratedFileDetails(Request $request){
        return $this->query_ReversalAccountsModule->get_GeneratedFileDetails($request);
    }

    public function getpreviewSeletedBeneficiaries(Request $request){
        return $this->query_ReversalAccountsModule->get_previewSeletedBeneficiaries($request);
    }

    public function ReturnGeneratedTextfile(Request $request){
        return $this->query_ReversalAccountsModule->Return_GeneratedTextfile($request);
    }


    



      
}
