<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $connection = 'system_db';
    protected $table = 'user';

    protected $fillable = ['name' , 'email' , 'password' , 'role'];
}
