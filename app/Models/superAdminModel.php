<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class superAdminModel extends Model
{
    protected $table = 'superadmin';
    protected $fillable = ['email' , 'password' , 'key'];
}
