<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResumeParser extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "resume_parsers";
    protected $guarded = [];
}
