<?php

namespace App\Modules\DownloadApp\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DownloadAppController extends Controller
{
    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if (!empty(session('supplier_id'))) {
            return view("DownloadApp::index");
        }else{
            return redirect('/login');
        }
    }

    public function getDownloadAppFiles(){
        return response()->download('downloads'.'/'.'sample1.xapk');
    }
}
