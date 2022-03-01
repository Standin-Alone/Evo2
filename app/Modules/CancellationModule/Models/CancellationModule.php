<?php

namespace App\Modules\CancellationModule\Models;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CancellationModule extends Model
{
    use HasFactory;

    public function get_CancellationModuleList($request){     
        $reg_code = sprintf("%02d",session('region'));
        if ($request->ajax()) {
            $getData = DB::table('dbp_returned_files as rf')
            ->select(DB::raw("rf.return_file_id,rf.file_name,r.province,rf.date_uploaded,sum(r.amount) as total_amount"))    
            ->leftJoin('dbp_return as r','r.return_file_id','rf.return_file_id')          
            ->where(function ($query) use($reg_code){
                if($reg_code != '13'){
                    $query->where('reg_code',$reg_code);
                }
            })
            ->where('rf.rfo_file_name',NULL)
            ->where('iscancelled',0)
            ->groupBy('rf.return_file_id')
            ->get();      
            return Datatables::of($getData)->make(true);
        }     
    }
    
    public function get_CancellationModuleDetails($request){ 
        $file_id = $request->file_id;   
        $reg_code = sprintf("%02d",session('region'));
        if ($request->ajax()) {
            $getData = DB::table('dbp_return')          
            ->where(function ($query) use($reg_code){
                if($reg_code != '13'){
                    $query->where('reg_code',$reg_code);
                }
            })
            ->where('return_file_id',$file_id)
            ->where('iscancelled',0)
            ->get();      
            return Datatables::of($getData)->make(true);
        }    
    }

    public function get_GeneratedCancellationModule($request){        
        $reg_code = sprintf("%02d",session('region'));
        if ($request->ajax()) {
            $getData = DB::table('dbp_returned_files as rf')
            ->select(DB::raw("rf.return_file_id,r.province,rf.date_uploaded,sum(r.amount) as total_amount,count(r.dbp_return_id) as total_count,rf.rfo_file_name,rf.rfo_date_created,rf.isdownloaded"))    
            ->leftJoin('dbp_return as r','r.return_file_id','rf.return_file_id')          
            ->where(function ($query) use($reg_code){
                if($reg_code != '13'){
                    $query->where('reg_code',$reg_code);
                }
            })  
            ->where('iscancelled',1)          
            ->where('rf.rfo_file_name','<>',NULL)
            ->groupBy('rf.return_file_id')           
            ->get();      
            return Datatables::of($getData)->make(true);
        }   
    }

    public function submit_CancellationModuleDetails($request){        
        $return_id = explode(",",$request->selecteddataid);
        for($i = 0; $i < count($return_id); ++$i) {
            DB::table('dbp_return')
            ->where('dbp_return_id', $return_id[$i])
            ->update(['iscancelled' => 1,'cancelled_by' => session('user_id'),'cancelled_date' => Carbon::now('GMT+8')]); 
        }        

        $base_path = "cancelled_files";
        $program = session('Default_Program_shortname'); 
        $program_year = now('GMT+8')->format('Y'); 
        $reg_shortname = sprintf("%02d",session('region'));

        $path1 = $base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname;
        $path2 = $program.'/'.$program_year.'/'.$reg_shortname;

        if (! File::exists($base_path)) {
            File::makeDirectory($base_path, $mode = 0775, true, true);
            if (! File::exists($base_path.'/'.$program)) {
                File::makeDirectory($base_path.'/'.$program, $mode = 0775, true, true);
                if (! File::exists($base_path.'/'.$program.'/'.$program_year)) {
                    File::makeDirectory($base_path.'/'.$program.'/'.$program_year, $mode = 0775, true, true);
                    if (! File::exists($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname)) {
                        File::makeDirectory($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname, $mode = 0775, true, true);
                    }
                }
            }            
        }else{
            if (! File::exists($base_path.'/'.$program)) {
                File::makeDirectory($base_path.'/'.$program, $mode = 0775, true, true);
                if (! File::exists($base_path.'/'.$program.'/'.$program_year)) {
                    File::makeDirectory($base_path.'/'.$program.'/'.$program_year, $mode = 0775, true, true);
                    if (! File::exists($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname)) {
                        File::makeDirectory($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname, $mode = 0775, true, true);
                    }
                }
            }
            else{                
                if (! File::exists($base_path.'/'.$program.'/'.$program_year)) {
                    File::makeDirectory($base_path.'/'.$program.'/'.$program_year, $mode = 0775, true, true);
                    if (! File::exists($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname)) {
                        File::makeDirectory($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname, $mode = 0775, true, true);
                    }
                }else{
                    if (! File::exists($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname)) {
                        File::makeDirectory($base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname, $mode = 0775, true, true);
                    }
                }
            }
        }

        $getGenerateData = DB::table('dbp_return as r')
            ->leftJoin('dbp_returned_files as rf','rf.return_file_id','r.return_file_id')
            ->whereIn('r.return_file_id', [$request->selectedfileid])
            ->where('iscancelled',1)
            ->get();

            $text_content = "";
            $text_nextline = "\n";

            $total_records = 0;
            $totalamt = 0;

            $program_File_Name = "";

        foreach ($getGenerateData as $key => $row) {
            $program_File_Name = str_replace("DISRET","DISCAN",$row->file_name);
            if($key == 0){
                $text_content = $row->funding_currency.'|'.date("m/d/Y", strtotime($row->date)).'|'.$row->imc.'|'.$row->account_number.
                '|'.$row->amount.'|'.$row->rsbsa_no.'|'.$row->fintech_provider.'|'.$row->first_name.'|'.$row->middle_name.'|'.$row->last_name.
                '|'.$row->street_purok.'|'.$row->city_municipality.'|'.$row->province.'||'.$row->contact_num.'||'.$row->remitter_name_1.'|'.$row->remitter_name_2.
                '|'.$row->remitter_name_3.'|'.$row->remitter_id.'||'.$row->remitter_address_1.'|'.$row->remitter_city.'|'.$row->remitter_province.'|'.'Cancellation'.'|';
            }else{
                $text_content = $text_content.$text_nextline.$row->funding_currency.'|'.date("m/d/Y", strtotime($row->date)).'|'.$row->imc.'|'.$row->account_number.
                '|'.$row->amount.'|'.$row->rsbsa_no.'|'.$row->fintech_provider.'|'.$row->first_name.'|'.$row->middle_name.'|'.$row->last_name.
                '|'.$row->street_purok.'|'.$row->city_municipality.'|'.$row->province.'||'.$row->contact_num.'||'.$row->remitter_name_1.'|'.$row->remitter_name_2.
                '|'.$row->remitter_name_3.'|'.$row->remitter_id.'||'.$row->remitter_address_1.'|'.$row->remitter_city.'|'.$row->remitter_province.'|'.'Cancellation'.'|';
            } 
            $total_records += 1;
            $totalamt += $row->amount;
        }

        Storage::disk($base_path)->put($path2.'/'.$program_File_Name, $text_content);        
        
        DB::table('dbp_returned_files')
            ->whereIn('return_file_id', [$request->selectedfileid])
            ->update(['rfo_file_name' => $program_File_Name,'rfo_created_by' => session('user_id'),'rfo_date_created' => Carbon::now('GMT+8')]); 


        return "saved!";
    }

    public function download_CancellationModuleTexfile($request){
        $file_name = $request->file_name;
        $base_path = "cancelled_files";
        $program = session('Default_Program_shortname'); 
        $program_year = now('GMT+8')->format('Y'); 
        $reg_shortname = sprintf("%02d",session('region'));
        $path = $base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname;

        DB::table('dbp_returned_files')
            ->where('rfo_file_name', $file_name)
            ->update(['isdownloaded' => 1,'downloaded_by' => session('user_id'),'download_date' => Carbon::now('GMT+8')]); 

        return response()->download($path.'/'.$file_name); 
    }

    public function get_GeneratedFileDetails($request){ 
        $rfo_file_name = $request->rfo_file_name;   
        $reg_code = sprintf("%02d",session('region'));
        if ($request->ajax()) {
            $getData = DB::table('dbp_return as r')       
            ->leftJoin('dbp_returned_files as rf','rf.return_file_id','r.return_file_id')
            ->where(function ($query) use($reg_code){
                if($reg_code != '13'){
                    $query->where('r.reg_code',$reg_code);
                }
            })
            ->where('rf.rfo_file_name',$rfo_file_name)
            ->where('r.iscancelled',1)
            ->get();      
            return Datatables::of($getData)->make(true);
        }    
    }
    
}
