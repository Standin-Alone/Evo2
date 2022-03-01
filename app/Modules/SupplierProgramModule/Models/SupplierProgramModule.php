<?php

namespace App\Modules\SupplierProgramModule\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierProgramModule extends Model
{
    use HasFactory;

    public function getSupplierProgramList()
    {

        $region = session()->get('region');

        $private_secret_key = session('private_secret_key');

        $query = DB::table('supplier as s')
                        ->select(DB::raw("s.supplier_id, s.supplier_name, sg.group_name as supplier_group, s.address,
                            AES_DECRYPT(s.bank_account_no,'".$private_secret_key."') as bank_account_no,
                            s.email, s.contact"))
                        ->leftJoin('supplier_group as sg', 'sg.supplier_group_id', 's.supplier_group_id')
                        ->when($region, function($query, $region){
                            if($region != 13){
                                $query->where('s.reg', '=', $region)->orderBy('s.supplier_name');
                            }
                            else{
                                $query->orderBy('s.supplier_name');
                            }
                        })
                        ->get();

        return $query;

    }

    public function get_programs(){

        $region = session()->get('region');

        $query = DB::table('programs as p')
                ->select('p.program_id', 'p.title')
                ->when($region, function($query, $region){
                    if($region != 13){
                        $query->orderBy('date_created', 'desc');
                    }
                    else{
                        $query->orderBy('date_created', 'desc');
                    }
                })
                ->get();

        return $query;

    }

    public function get_program_items(){

        $region = session()->get('region');

        $query = DB::table('program_items as pi')
                        ->select('pi.item_id', 'pi.item_name')
                        ->when($region, function($query, $region){
                            if($region != 13){
                                $query->where('pi.active', '=', '1')->orderBy('date_created', 'desc');
                            }
                            else{
                                $query->where('pi.active', '=', '1')->orderBy('date_created', 'desc');
                            }
                        })
                        ->get();

        return $query;

    }

    public function get_selected_program(){

    }

    public function get_program_items_details($program_item_id){

        $query = DB::table('program_items as pi')
                        ->select('pi.item_id', 'pi.item_name', 'pi.unit_measure', 'pi.ceiling_amount')
                        ->whereIn('pi.item_id', $program_item_id)
                        ->orderBy('date_created', 'desc')
                        ->get();

        return $query;

    }

    public function insert_new_setup($program_id, $supplier_id, $item_id){

        foreach($item_id as $v2){
            $query = DB::table('supplier_programs')
                            ->insert([
                                        ['sub_id'=> Uuid::uuid4(), 'supplier_id'=> $supplier_id, 'program_id'=> $program_id, 'item_id'=> $v2]
                                    ]);
        }

        return $query;

    }

    public function supplier_program_overview_query(){

        $region = session()->get('region');

        $query = DB::table('supplier_programs as sp')
                        ->select('s.supplier_name', 'p.title', 'pi.item_name', 'pi.unit_measure', 'pi.ceiling_amount', 'sp.date_created', 'sp.active')
                        ->leftJoin('supplier as s', 's.supplier_id', '=', 'sp.supplier_id')
                        ->leftJoin('programs as p', 'p.program_id', '=', 'sp.program_id')
                        ->leftJoin('program_items as pi', 'pi.item_id', '=', 'sp.item_id')
                        ->when($region, function($query, $region){
                            if($region != 13){
                                $query->where('sp.active', '=', "1")
                                      ->orderByDesc('sp.date_created');
                            }
                            else{
                                $query->where('sp.active', '=', "1")
                                      ->orderByDesc('sp.date_created');
                            }
                        })
                        ->get();

        return $query;
    }

    // public function update_create_setup(){

    //     // updateOrInsert() = update if the user has already exists and insert if not yet exists, create new data
    //     $query = DB::table('')->updateOrInsert(
    //                 ['user_id' => $user_id],
    //                 ['user_id' => $user_id,'item_id' => $item_id]
    //             );

    //     return $query;

    // }
}
