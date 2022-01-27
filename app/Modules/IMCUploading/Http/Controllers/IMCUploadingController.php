<?php

namespace App\Modules\IMCUploading\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\IMCImport;
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

    public function get_ingest_imc_files(){
        $get_records = db::table('imc_files as if')
                            ->select('if.file_name',DB::raw('IF(total_inserted is NULL,0,total_inserted) as total_inserted'),DB::raw('IF(total_rows is NULL,0,total_rows) as total_rows'),'if.date_created','imc_file_id')                            
                            ->where('created_by_user_id',session('user_id'))                         
                            ->where('if.status','1')                        
                            ->orderBy('if.date_created','DESC')                            
                            ->get();


        return Datatables::of($get_records)->make(true);   

    }
    

    // ingest csv imc files to database
    public function ingest_imc_files(){
        $file_name = request('file_name');
        $provider  = '';
        $result = '';
        $upload_path = 'temp_excel/imc';
        $count_error = "";
        $upload_folder  = $upload_path.'/'.Carbon::now()->year;

        if(!File::isDirectory($upload_path)){
            
            File::makeDirectory($upload_path, 0775, true);                                
            $by_year_path = $upload_path.'/'.Carbon::now()->year;
            if(!File::isDirectory($by_year_path)){

                File::makeDirectory($by_year_path, 0775, true);
            }
        }

        $error_storage = [];
        foreach($file_name as $item_filename){
            $count = '';

            $get_filename = $item_filename;
    
            
            $check_file = db::table('imc_files')->where('file_name',$get_filename)->whereColumn('total_inserted','total_rows')->take(1)->get();


            $imc_import = new IMCImport($get_filename);
                
                                                    
            Excel::import($imc_import,$upload_folder.'/'.$get_filename);
            $import_file = $imc_import->get_result();
         

            
            if($import_file){
                
                // check if import file has errors
                if(count($import_file['error_data']) == 0){

                    db::table('imc_files')
                        ->where('file_name',$get_filename)
                        ->update(['status'=>'0']);
                        
                    
                }else{

                    if(count($error_storage) > 0){
                        $error_storage = array_merge($error_storage[0], $import_file['error_data']);                        

                    }else{
                        array_push($error_storage, $import_file['error_data'] );                        
                    }  
                }


          
            }else{
                return 'false';
            }
        }

        if($count_error == 0){
            return  json_encode(["message" => 'true','error_data' => $error_storage]);
            
        
        }else{
            return  json_encode(["message" => 'false']);
            
        }


   
    }

    // upload imc files to file server
    public function upload_imc_files()
    {

        $file = request()->file('file');
        $provider  = '';
        $result  = '';
        $upload_path = 'temp_excel/imc';
  

        $upload_folder  = $upload_path.'/'.Carbon::now()->year;

        if(!File::isDirectory($upload_path)){
            
            File::makeDirectory($upload_path, 0775, true);                                
            $by_year_path = $upload_path.'/'.Carbon::now()->year;
            if(!File::isDirectory($by_year_path)){

                File::makeDirectory($by_year_path, 0775, true);
            }
        }

                
        foreach($file as $key => $item_file){


            if(is_file($item_file)){

                $get_filename = $item_file->getClientOriginalName();
                $check_file_exist = db::table('imc_files')->where('file_name',$get_filename)->take(1)->get();
                if($check_file_exist->isEmpty()){

                    if(Storage::disk('local')->put($upload_folder.'/'.$get_filename,file_get_contents($item_file))){

                    
                    $insert_to_db = db::table('imc_files')->insert(['file_name' => $get_filename ,'created_by_user_id' => session('user_id') ]);

                        if($insert_to_db){
                            $result =  json_encode(["message" => 'true']);
                        }else{
                            $result =  json_encode(["message" => 'false']);
                        }
                    }else{
                        $result =  json_encode(["message" => 'false']);
                    }   
                    
                    
                }else{
                   
                    $result =  json_encode(["message" => 'false']);
                }
            }
        }

        return $result;

    }
}
