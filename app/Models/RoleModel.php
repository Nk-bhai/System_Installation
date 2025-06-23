<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Schema;

class RoleModel extends Model
{
    protected $table = 'role';
    protected $fillable = ['role_name' , 'permissions'];

    public function users()
{
    // return $this->hasMany(UserModel::class, 'role_id');
    return $this->hasMany(UserModel::class, 'role_id', 'id');
}
// public function users()
// {
//     // dd("hello");
//     if (!Schema::hasTable('user')) {
//         return $this->hasMany(UserModel::class, 'role_id', 'id')->whereRaw('1 = 0');
//     }

//     return $this->hasMany(UserModel::class, 'role_id', 'id');
// }

}
