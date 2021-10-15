<?php

namespace App\Modules\ProgramIntervention\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\ProgramIntervention\Models\ProgramInterventionModel;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class ProgramInterventionController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(){
       # parent::__construct();
        $this->program_model = new ProgramInterventionModel;
    }

    public function index(){
    	return $this->welcome();
    }

    public function welcome()
    {
        return view("ProgramIntervention::index");
    }

    public function create_intervention(Request $request){
        $prog_title = trim($request->prog_title);
        $prog_alias = trim($request->prog_alias);
        $prog_remitter = trim($request->prog_remitter);
        $prog_desc  = trim($request->description);
        $amount     = str_replace(',', '', $request->amount);

        $prog_range = $request->prog_range;

        $prog_range = explode('-', $prog_range);

        $start_date = date('Y-m-d',strtotime(trim($prog_range[0])));
        $end_date   = date('Y-m-d',strtotime(trim($prog_range[1])));

        $data = array(
            'program_id'          => Uuid::uuid4()->toString(),
            'title'               => $prog_title,
            'shortname'           => $prog_alias,
            'remitter_id'         => $prog_remitter,
            'description'         => $prog_desc,
            'amount'              => number_format(floatval($amount), 2, '.', ''),
            'duration_start_date' => $start_date,
            'duration_end_date'   => $end_date
        );
        
        $response = $this->program_model->create_intervention($data);
        return $response;
    }

}
