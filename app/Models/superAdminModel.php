<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class superAdminModel extends Model
{
    protected $table = 'superAdmin';
    protected $fillable = ['email' , 'password' , 'key'];
}
