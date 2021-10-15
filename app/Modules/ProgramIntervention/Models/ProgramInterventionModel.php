<?php

namespace App\Modules\ProgramIntervention\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProgramInterventionModel extends Model
{
    use HasFactory;

    protected $program_tbl = 'programs';

    public function create_intervention($data){
    	$output['result'] = 'fail';

    	$query = DB::table($this->program_tbl)->insert($data);

    	if($query){
    		$output['result']= 'success';
    	}

    	return $output;
    }
}
