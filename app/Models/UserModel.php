<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = 'user_crud';

    protected $fillable = ['name' , 'age'];
}
