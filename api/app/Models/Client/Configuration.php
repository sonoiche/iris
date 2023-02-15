<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $table = "configurations";
    protected $guarded = [];
    protected $appends = ['display_logo'];

    public function getDisplayLogoAttribute()
    {
        $logo = $this->attributes['logo'] ?? '';
        if($logo) {
            return config('app.api_url').'/storage/'.$logo;
        }

        return '';
    }
}
