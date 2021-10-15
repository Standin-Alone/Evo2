<?php

namespace App\Modules\ProgramInterventionOverview\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\ProgramInterventionOverview\Models\ProgramInterventionOverviewModel;
#use Illuminate\Support\Facades\Input;

class ProgramInterventionOverviewController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */

    protected $programs;

    public function __construct(){
       # parent::__construct();
        $this->program_model = new ProgramInterventionOverviewModel;
    }

    public function index(){
        
    	return $this->welcome();
    }

    public function welcome()
    {
        return view("ProgramInterventionOverview::index");
    }

    public function get_programs(){
        $programs = $this->program_model->get_programs('1');
        return $programs ;
    }

    public function get_program_liquidation(Request $request){
        $program_id = $request->program_id;

        $liquidation = $this->program_model->get_program_liquidation($program_id);
        return $liquidation;
    }

    public function get_program_payouts(Request $request){
        $payouts = $this->program_model->get_program_payouts($request->all());
        return $payouts;
    }

    public function lock_intervention(Request $request){
        $program_id = $request->dt_program_id;
        $status     = $request->status;

        $locked_result = $this->program_model->lock_intervention($program_id, $status);
        return $locked_result;
    }

}
