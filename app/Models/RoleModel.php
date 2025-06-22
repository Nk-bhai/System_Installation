<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    protected $table = 'role';
    protected $fillable = ['role_name' , 'permissions'];

    public function users()
{
    return $this->hasMany(UserModel::class, 'role_id');
}
}
