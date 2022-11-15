<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "permissions";
    protected $guarded = [];

    public function getCanReadAttribute()
    {
        $read = isset($this->attributes['can_read']) ? $this->attributes['can_read'] : '';
        if($read) {
            return ($read == 1) ? true : false;
        }

        return '';
    }

    public function getCanWriteAttribute()
    {
        $write = isset($this->attributes['can_write']) ? $this->attributes['can_write'] : '';
        if($write) {
            return ($write == 1) ? true : false;
        }

        return '';
    }

    public function getCanDeleteAttribute()
    {
        $delete = isset($this->attributes['can_delete']) ? $this->attributes['can_delete'] : '';
        if($delete) {
            return ($delete == 1) ? true : false;
        }

        return '';
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
