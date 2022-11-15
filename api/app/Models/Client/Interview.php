<?php

namespace App\Models\Client;

use App\Models\Client\Employer\JobOrderPosition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Interview extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "interviews";
    protected $guarded = [];

    public function position()
    {
        return $this->belongsTo(JobOrderPosition::class, 'position_id');
    }
}
