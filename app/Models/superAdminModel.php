<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class superAdminModel extends Authenticatable
{
    protected $table = 'superadmin';

    protected $fillable = ['email', 'password', 'key'];

}