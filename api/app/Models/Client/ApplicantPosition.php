<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicantPosition extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "applicant_positions";
    protected $guarded = [];
}
