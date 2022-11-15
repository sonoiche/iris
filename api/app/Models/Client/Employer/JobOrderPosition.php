<?php

namespace App\Models\Client\Employer;

use App\Models\Client\Lineup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobOrderPosition extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "job_order_positions";
    protected $guarded = [];

    public function lineup_positions()
    {
        return $this->hasMany(Lineup::class, 'position_id');
    }
}
