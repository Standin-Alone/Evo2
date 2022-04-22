<?php

namespace App\Modules\VoucherListing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchList extends Model
{

    use HasFactory;
    public $table = "vw_batch_lists";
    protected $fillable = [
        'CLAIMED_VOUCH',
        'UNCLAIMED_VOUCH',
        'TOTAL_VOUCH',
        'batch_desc',
        'batch_file',
        'uploaded_date',
        'uploaded_by_id',
        'batch_code',
    ];  
}
