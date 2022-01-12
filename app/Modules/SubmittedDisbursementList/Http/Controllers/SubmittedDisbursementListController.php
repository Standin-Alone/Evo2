<?php

namespace App\Modules\SubmittedDisbursementList\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\SubmittedDisbursementList\Models\SubmittedDisbursementList;

class SubmittedDisbursementListController extends Controller
{

    public function __construct(Request $request)
    {
        $this->QuerySubmittedDisbursementList = new SubmittedDisbursementList();
    }

    public function index()
    {
        return view("SubmittedDisbursementList::index");
    }

    public function getSubmittedDisbursementList(Request $request){
        return $this->QuerySubmittedDisbursementList->get_SubmittedDisbursementList();
    }

    public function postDisbursementfile(Request $request){
        return $this->QuerySubmittedDisbursementList->post_Disbursementfile($request->dbpbatchid);
    }
}
