<?php

namespace App\Models\Client;

use Carbon\Carbon;
use App\Models\Client\ApplicantStatus;
use App\Models\Client\Employer\JobOrder;
use App\Models\Client\Employer\JobOrderPosition;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client\Employer\Principal;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lineup extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "lineups";
    protected $guarded = [];
    protected $appends = ['created_at_display'];

    public function getCreatedAtDisplayAttribute()
    {
        $date = isset($this->attributes['created_at']) ? $this->attributes['created_at'] : '';
        return Carbon::parse($date)->format('F d, Y');
    }

    public function employer()
    {
        return $this->belongsTo(Principal::class, 'principal_id');
    }

    public function job_order()
    {
        return $this->belongsTo(JobOrder::class, 'job_order_id');
    }

    public function position()
    {
        return $this->belongsTo(JobOrderPosition::class, 'position_id');
    }

    public function lineup_status()
    {
        return $this->belongsTo(ApplicantStatus::class, 'lineup_status_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id', 'applicant_number');
    }
}
