<?php

namespace App\Models;

use Carbon\Carbon;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    protected $appends = ['fullname','display_photo','created_at_display'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    CONST STATUS_ACTIVE = 'Active';
    CONST STATUS_INACTIVE = 'Inactive';

    public function getFullnameAttribute()
    {
        $fname = $this->attributes['fname'];
        $lname = $this->attributes['lname'];

        return $fname.' '.$lname;
    }

    public function getDisplayPhotoAttribute()
    {
        $fname = $this->attributes['fname'];
        $lname = $this->attributes['lname'];
        $photo = isset($this->attributes['photo']) ? $this->attributes['photo'] : '';
        if($photo) {
            return config('app.api_url').'/storage/'.$photo;
        }

        $fullname = $fname.' '.$lname;
        return 'https://ui-avatars.com/api/?name='.$fullname.'&background=random&size=125';
    }

    public function getCreatedAtDisplayAttribute()
    {
        $date = $this->attributes['created_at'];
        return Carbon::parse($date)->format('d M, Y');
    }
}
