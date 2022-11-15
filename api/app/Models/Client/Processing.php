<?php

namespace App\Models\Client;

use App\Models\Client\Country;
use App\Models\Client\Employer\Principal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Processing extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "processings";
    protected $guarded = [];
    protected $appends = ['country_name','principal_name'];

    public function getCountryNameAttribute()
    {
        $country = $this->country;
        return isset($country) ? $country->name : '';
    }

    public function getPrincipalNameAttribute()
    {
        return (isset($this->principal) && $this->principal->name !== '') ? $this->principal->name : '';
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function principal()
    {
        return $this->belongsTo(Principal::class, 'principal_id');
    }
}
