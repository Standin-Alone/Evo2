<?php

namespace App\Modules\VoucherListing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherList extends Model
{

    use HasFactory;
    public $table = "voucher";
    protected $fillable = [
        'rsbsa_no',
        'reference_no',
        'first_name',
        'middle_name',
        'last_name',
        'ext_name',
    ];  
}
