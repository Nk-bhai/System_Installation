<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $connection = 'nk_db';
    protected $table = 'user';

    protected $fillable = ['name' , 'email' , 'password' , 'role'];
}
