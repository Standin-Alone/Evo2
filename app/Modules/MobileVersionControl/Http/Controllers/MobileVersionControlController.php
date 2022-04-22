<?php

namespace App\Modules\MobileVersionControl\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
class MobileVersionControlController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("MobileVersionControl::index");
    }   

    
    
    public function show()
    {
        $get_records = db::table('mobile_utility')
                                ->get();

        return Datatables::of($get_records)->make(true);
    }   


    public function add_apk(){
        
        $file    = request()->file('file');
        $version = request('version');
        

        $get_original_name = $file->getClientOriginalName();


            
        $upload_file = Storage::disk('downloads')->put($get_original_name,$file);

        $check_version = db::table('mobile_utility')->select('')
                            ->where('version',$version)->first();
        
                    
        if(!$check_version){

        

            if(!Storage::disk('downloads')->exists($get_original_name)){


            
                $insert_db = db::table('mobile_utility')
                ->insert([
                    'version' => $version,                
                    'filename' => $get_original_name,                
                ]); 

                if($insert_db){
                    return json_encode(['status'=>'true','Message'=>'Successfully added new version of apk.']);

                }else{
                    return json_encode(['status'=>'false','Message'=>'Unsuccessful Import']);
                }

            }else{
                return json_encode(['status'=>'false','Message'=>'APK filename already exist.']);
            }

        }else{
            return json_encode(['status'=>'false','Message'=>'']);
        }

    
    }
}
