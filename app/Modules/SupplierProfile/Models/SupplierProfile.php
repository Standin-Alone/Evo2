<?php

namespace App\Modules\SupplierProfile\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierProfile extends Model
{
    use HasFactory;

    protected $table = "supplier";
    public $timestamps = false;
    
}
