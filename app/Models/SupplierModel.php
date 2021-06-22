<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierModel extends Model
{        
    protected $table = 'supplier'; // name of table
    protected $timestamp = false; // To avoid conflict in storing of data becase of created date.
    protected $fillable = ['']; //fillable collumns
}
