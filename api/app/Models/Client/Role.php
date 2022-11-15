<?php

namespace App\Models\Client;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "roles";
    protected $guarded = [];

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'role_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
