<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rfolocation extends Model
{
    use HasFactory;

    public function rfo_location()
    {
        return $this->hasMany(registration::class);
    }
}
