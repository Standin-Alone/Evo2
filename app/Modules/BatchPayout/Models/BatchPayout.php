<?php

namespace App\Modules\BatchPayout\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchPayout extends Model
{
    use HasFactory;

    protected $table = "payout_gif_batch";
    protected $fillable = ['batch_id','amount','application_number','supplier_id','transac_date','description'];
}
