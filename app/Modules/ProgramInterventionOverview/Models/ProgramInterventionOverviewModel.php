<?php

namespace App\Modules\ProgramInterventionOverview\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProgramInterventionOverviewModel extends Model
{
    use HasFactory;
    protected $programs_tbl 		     = 'programs';
    protected $vw_program_liquidation    = 'vw_program_liquidation';
    protected $vw_program_payout_batches = 'vw_program_payout_batches';

    public function get_programs($active){
    	$programs = DB::table($this->programs_tbl)
    				  //->where('status', $active)
                      ->orderBy('date_created', 'desc')
    				  ->get();

    	return $programs;
    }

    public function get_program_liquidation($program_id){
    	$program_liquidation = DB::table($this->vw_program_liquidation)
    							 ->where('program_id', $program_id)
    							 ->get();
   
   		$shortname 		  = $program_liquidation[0]->shortname;
   		$amount 		  = floatval($program_liquidation[0]->amount);
   		$disbursed_amount = floatval($program_liquidation[0]->disbursed_amount);

   		$total_liquidated = ($disbursed_amount / $amount) * 100;
   		$remaining = $amount - $disbursed_amount;
   		$remaining = ($amount / $remaining) * 100;

        $info = array(
            'author'       => $program_liquidation[0]->created_by_fullname,
            'title'        => $program_liquidation[0]->title,
            'program_id'   => $program_liquidation[0]->program_id,
            'description'  => $program_liquidation[0]->description,
            'date_created' => date('F d, Y', strtotime($program_liquidation[0]->date_created))
        );

   		$data = array($shortname,[
   			['Disbursed', $total_liquidated],
   			['Remaining', $disbursed_amount == 0 ? 100 : $remaining]
   		], $info);

    	return $data;
    }

    public function get_program_payouts($requests){
    	$program_id = $requests['program_id'];

        $payouts = DB::table($this->vw_program_payout_batches)
        			 ->select(['supplier_name','application_number', 'amount', 'transac_date', 'supplier_id'])
        			 ->where('program_id', $program_id);

        return Datatables::of($payouts)
                         ->editColumn('transac_date', function($payouts) {
                            return date('F d, Y', strtotime($payouts->transac_date));
                         })
        	   			 ->make(true);
    }

    public function lock_intervention($program_id, $status){
        $result = 'fail';
        $status = ($status == '1') ? '0' : '1';
        //DB::enableQueryLog();

        $lock = DB::table($this->programs_tbl)
                  ->where('program_id', $program_id)
                  ->update(['status' => $status]);

        //dd(DB::getQueryLog());
        if($lock){
            $result = 'success';
        }

        $data = [
            'status' => $status,
            'result' => $result
        ];

        return $data;
    }
}
