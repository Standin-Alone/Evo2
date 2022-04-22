<?php

namespace App\Modules\ReversalAccountsModule\Models;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReversalAccountsModule extends Model
{
    use HasFactory;

    public function get_ReversalAccountsModuleList($request){     
        $reg_code = sprintf("%02d",session('reg_code'));
        if ($request->ajax()) {
            // DB::enableQueryLog();
            $getData = DB::table('kyc_profiles as kyc')
            ->select(DB::raw("kyc.kyc_file_id,kyc.kyc_id,kyc.province,kyc.date_uploaded,count(distinct kyc.rsbsa_no) as total_count,sum(".session('disburse_amount').") as total_amount,k.file_name"))   
            ->leftJoin('kyc_files as k','k.kyc_file_id','kyc.Kyc_file_id')
            ->where('kyc.program_id',session('Default_Program_Id')) 
            ->where(function ($query) use($reg_code){
                if($reg_code != '13'){
                    $query->where('kyc.reg_code',$reg_code)->where('kyc.agency_id',session('user_agency_id'));
                }
            })
            ->where('kyc.isremove',1)
            ->where('kyc.isrevert',0)
            ->groupBy('kyc.kyc_file_id')
            ->get();      
            // dd(DB::getQueryLog());
            return Datatables::of($getData)->make(true);
        }   
    }
    
    public function get_ReversalAccountsModuleDetails($request){ 
        $file_id = $request->file_id;   
        $reg_code = sprintf("%02d",session('region'));
        if ($request->ajax()) {
            $getData = DB::table('kyc_profiles as kyc')    
                ->select(DB::raw("kyc.kyc_id,kyc.rsbsa_no,kyc.first_name,kyc.middle_name,kyc.last_name,kyc.ext_name,kyc.province,kyc.municipality,kyc.street_purok,".session('disburse_amount')." as amount")) 
                ->where(function ($query) use($reg_code){
                    if($reg_code != '13'){
                        $query->where('kyc.reg_code',$reg_code)->where('kyc.agency_id',session('user_agency_id'));
                    }
                })
                ->where('kyc.kyc_file_id',$file_id)                
                ->get();      
            return Datatables::of($getData)->make(true);
        }    
    }

    public function get_GeneratedReversalAccountsModule($request){        
        // DB::enableQueryLog();
        $reg_code = sprintf("%02d",session('region'));
        if ($request->ajax()) {
            $getData = DB::table('kyc_profiles as kyc')
            ->select(DB::raw("kyc.kyc_id,kyc.province,sum(".session('disburse_amount').") as total_amount,count(distinct kyc.kyc_id) as total_count,kyc.revert_file_name,kyc.revert_date_created,kyc.revert_isdownloaded"))    
            ->where('kyc.program_id',session('Default_Program_Id')) 
            ->where(function ($query) use($reg_code){
                if($reg_code != '13'){
                    $query->where('kyc.reg_code',$reg_code)->where('kyc.agency_id',session('user_agency_id'));
                }
            })
            ->where('kyc.isrevert',1)          
            ->whereNotNull('kyc.revert_file_name')
            ->groupBy('kyc.revert_file_name')           
            ->get();      
            // dd(DB::getQueryLog());
            return Datatables::of($getData)->make(true);
        }   
    }

    public function submit_ReversalAccountsModuleDetails($request){  
       
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
        
        $getGenerateData = DB::table('kyc_profiles as kyc')
            ->select(DB::raw('kyc.kyc_id,kyc.rsbsa_no,kyc.first_name,kyc.middle_name,kyc.last_name,kyc.ext_name,kyc.id_number,kyc.gov_id_type,kyc.street_purok,kyc.barangay,kyc.municipality,kyc.district,
                    kyc.province,kyc.region,kyc.birthdate,kyc.place_of_birth,kyc.mobile_no,kyc.sex,kyc.nationality,kyc.profession,kyc.sourceoffunds,kyc.mothers_maiden_name,kyc.no_parcel,
                    kyc.total_farm_area,kyc.account_number,kyc.remarks,f.file_name'))
            ->leftJoin('kyc_files as f','f.kyc_file_id','kyc.kyc_file_id')        
            ->whereIn('kyc.kyc_id', explode(",",$request->selectedkycfileid))
            ->where('isremove','1')
            ->get();
            $text_content = "";
            $text_nextline = "\n";

            $total_records = 0;
            $totalamt = 0;

            $program_File_Name = "";

        foreach ($getGenerateData as $key => $row) {
            $program_File_Name = str_replace(".xlsx","",$row->file_name);
            $program_File_Name = str_replace(".xls","",$program_File_Name);
            $program_File_Name = str_replace("ONBINT","RACINT",$program_File_Name);
            $program_File_Name = str_replace("ONBSUC","RACINT",$program_File_Name);
            $program_File_Name = str_replace("_v1","",$program_File_Name);
            $program_File_Name = str_replace("_V1","",$program_File_Name);

            $getRAC_file_Series = "001"; 
            DB::transaction(function() use (&$getRAC_file_Series,&$row){
                $get_fileseries = DB::table('kyc_profiles as kyc')->select(DB::raw("MAX(RIGHT(concat('000',right(kyc.revert_file_series,3)+1),3)) as revert_file_series"))
                ->where('kyc.kyc_id',$row->kyc_id)
                ->groupBy('kyc.revert_file_series')
                ->orderBy('kyc.revert_file_series', 'DESC')
                ->take(1)
                ->get();
                foreach ($get_fileseries as $key => $series) {
                    $getRAC_file_Series = $series->revert_file_series;
                } 
            });

            $program_File_Name = $program_File_Name.'_'.$getRAC_file_Series.'_v1';                    

            if($key == 0){
                $text_content = $row->rsbsa_no.'|'.$row->first_name.'|'.$row->middle_name.'|'.$row->last_name.'|'.$row->ext_name.'|'.$row->id_number.'|'.$row->gov_id_type.'|'.$row->street_purok.'|'.$row->barangay.'|'.$row->municipality.'|'.$row->district
                .'|'.$row->province.'|'.$row->region.'|'.$row->birthdate.'|'.$row->place_of_birth.'|'.$row->mobile_no.'|'.$row->sex.'|'.$row->nationality.'|'.$row->profession.'|'.$row->sourceoffunds.'|'.$row->mothers_maiden_name.'|'.$row->no_parcel
                .'|'.$row->total_farm_area.'|'.$row->account_number.'|Reversal';
            }else{
                $text_content = $text_content.$text_nextline.$row->rsbsa_no.'|'.$row->first_name.'|'.$row->middle_name.'|'.$row->last_name.'|'.$row->ext_name.'|'.$row->id_number.'|'.$row->gov_id_type.'|'.$row->street_purok.'|'.$row->barangay.'|'.$row->municipality.'|'.$row->district
                .'|'.$row->province.'|'.$row->region.'|'.$row->birthdate.'|'.$row->place_of_birth.'|'.$row->mobile_no.'|'.$row->sex.'|'.$row->nationality.'|'.$row->profession.'|'.$row->sourceoffunds.'|'.$row->mothers_maiden_name.'|'.$row->no_parcel
                .'|'.$row->total_farm_area.'|'.$row->account_number.'|Reversal';
            } 
            
        }
        
        Storage::disk($base_path)->put($path2.'/'.$program_File_Name.'.txt', $text_content);        
        
        DB::table('kyc_profiles')
            ->whereIn('kyc_id', explode(",",$request->selectedkycfileid))
            ->update(['isrevert' => 1,'date_revert' => Carbon::now('GMT+8'),
            'revert_file_name' => $program_File_Name,'revert_created_by' => session('user_id'),'revert_date_created' => Carbon::now('GMT+8'),'revert_file_series'=>$getRAC_file_Series]); 

        return "saved!";
    }

    public function download_ReversalAccountsModuleTexfile($request){
        $file_name = $request->file_name;
        $base_path = "cancelled_files";
        $program = session('Default_Program_shortname'); 
        $program_year = now('GMT+8')->format('Y'); 
        $reg_shortname = sprintf("%02d",session('region'));
        $path = $base_path.'/'.$program.'/'.$program_year.'/'.$reg_shortname;

        DB::table('kyc_profiles')
            ->where('revert_file_name', $file_name)
            ->update(['revert_isdownloaded' => 1,'revert_downloaded_by' => session('user_id'),'revert_download_date' => Carbon::now('GMT+8')]); 

        return response()->download($path.'/'.$file_name.'.txt'); 
    }

    public function get_GeneratedFileDetails($request){ 
        $reg_code = sprintf("%02d",session('region'));
        if ($request->ajax()) {
            $getData = DB::table('kyc_profiles as kyc')    
                ->select(DB::raw("kyc.kyc_id,kyc.rsbsa_no,kyc.first_name,kyc.middle_name,kyc.last_name,kyc.ext_name,kyc.province,kyc.municipality,kyc.street_purok,".session('disburse_amount')." as amount")) 
                ->where(function ($query) use($reg_code){
                    if($reg_code != '13'){
                        $query->where('kyc.reg_code',$reg_code)->where('kyc.agency_id',session('user_agency_id'));
                    }
                })
                ->where('kyc.revert_file_name',explode(",",$request->revert_file_name))              
                ->get();      
            return Datatables::of($getData)->make(true);
        }  
    }

    public function get_previewSeletedBeneficiaries($request){
        $reg_code = sprintf("%02d",session('region'));
        if ($request->ajax()) {
            $getData = DB::table('kyc_profiles as kyc')    
                ->select(DB::raw("kyc.kyc_id,kyc.rsbsa_no,kyc.first_name,kyc.middle_name,kyc.last_name,kyc.ext_name,kyc.province,kyc.municipality,kyc.street_purok,".session('disburse_amount')." as amount")) 
                ->where(function ($query) use($reg_code){
                    if($reg_code != '13'){
                        $query->where('kyc.reg_code',$reg_code)->where('kyc.agency_id',session('user_agency_id'));
                    }
                })
                ->whereIn('kyc.kyc_id',explode(",",$request->selectedkycfileid))              
                ->get();      
            return Datatables::of($getData)->make(true);
        }  
    }

    public function Return_GeneratedTextfile($request){
        DB::table('kyc_profiles')
        ->where('revert_file_name', $request->selectedrfofilenameid)
        ->update(['isrevert'=>0,'date_revert'=>NULL,'revert_file_name'=>NULL,'revert_created_by'=>NULL,'revert_date_created'=>NULL,'revert_file_series'=>0,
        'revert_isdownloaded'=>0,'revert_downloaded_by'=>NULL,'revert_download_date'=>NULL]); 
    }
    
}
