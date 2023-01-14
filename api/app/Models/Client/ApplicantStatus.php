<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicantStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "applicant_statuses";
    protected $guarded = [];

    CONST DEPLOYED = 9;
}
