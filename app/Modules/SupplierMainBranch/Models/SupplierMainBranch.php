<?php

namespace App\Modules\SupplierMainBranch\Models;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierMainBranch extends Model
{
    use HasFactory;

    public function insert_MainBranch($group_name,$address){
        $insert_MainBranch_values = DB::transaction(function() use(&$group_name,&$address){
            DB::table('supplier_group')->insert([
                'supplier_group_id'=> Uuid::uuid4(),
                'group_name'=> strtoupper($group_name),
                'address'=> strtoupper($address),
                'created_by_id'=> session('user_id'),
                'created_by_fullname'=> session('user_fullname'),
                'created_agency'=> session('agency_loc'),
                'email' => session('email'),
                'date_created'=> Carbon::now('GMT+8'),
            ]);
        });
        return $insert_MainBranch_values;
    }

    public function Update_MainBranch($group_id,$group_name,$address){
        $Update_MainBranch_values = DB::transaction(function() use(&$group_id,&$group_name,&$address){
            DB::table('supplier_group')
                ->where('supplier_group_id',$group_id)   
                ->update(['group_name' => strtoupper($group_name),'address'=> strtoupper($address)]);
        });
        return $Update_MainBranch_values;
    }

    public function Remove_MainBranch($group_id){
        $Remove_MainBranch_values = DB::transaction(function() use(&$group_id){
            DB::table('supplier_group')->where('supplier_group_id',$group_id)->delete();
        });
        return $Remove_MainBranch_values;
    }

    // public function request_approval_to_rfo(){

    //     $query = MailController::send_request_approval_to_rfo();

    //     return $query;

    // }
}
