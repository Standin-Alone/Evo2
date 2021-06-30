<?php

namespace App\Modules\RolesAndPermissions\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolesAndPermissions extends Model
{
    

    protected $table = 'sys_access_matrix';
    public $timestamps = false;
    protected $fillable = ['role_id','sys_permission_id','sys_module_id','status'];
}
