<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeoMap extends Model
{
    use HasFactory;

    protected $table = "geo_map";

    public $timestamps = false;
}
