<?php

namespace App\Models\Client\Employer;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Client\Lineup;
use App\Models\Client\ApplicantStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Client\Employer\JobOrderPosition;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "job_orders";
    protected $guarded = [];
    protected $appends = ['date_receive_display','date_needed_display','date_expiry_display','principal_name','assigned_user_list','lineup_status'];

    public function principal()
    {
        return $this->belongsTo(Principal::class, 'principal_id');
    }

    public function positions()
    {
        return $this->hasMany(JobOrderPosition::class, 'job_order_id');
    }

    public function lineups()
    {
        return $this->hasMany(Lineup::class, 'job_order_id');
    }

    public function getDateReceiveDisplayAttribute()
    {
        $date = isset($this->attributes['date_receive']) ? $this->attributes['date_receive'] : '';
        return validDate($date) ? Carbon::parse($date)->format('M d, Y'): '';
    }

    public function getDateNeededDisplayAttribute()
    {
        $date = isset($this->attributes['date_needed']) ? $this->attributes['date_needed'] : '';
        return validDate($date) ? Carbon::parse($date)->format('M d, Y'): '';
    }

    public function getDateExpiryDisplayAttribute()
    {
        $date = isset($this->attributes['date_expiry']) ? $this->attributes['date_expiry'] : '';
        return validDate($date) ? Carbon::parse($date)->format('M d, Y'): '';
    }

    public function getPrincipalNameAttribute()
    {
        return (isset($this->principal) && $this->principal->name !== '') ? $this->principal->name : '';
    }

    public function getAssignedUserListAttribute()
    {
        $content  = [];
        $user_ids = isset($this->attributes['assigned_users']) ? $this->attributes['assigned_users'] : '';
        if($user_ids) {
            $assigned_users = explode(',', $user_ids);
            $users = User::whereIn('id', $assigned_users)->orderBy('fname')->get();
            foreach ($users as $user) {
                $content[] = [
                    'id' => $user->id,
                    'name' => $user->fullname
                ];
            }
        }

        return $content;
    }

    public function getLineupStatusAttribute()
    {
        $lineupstatus = ApplicantStatus::all();
        $arr_status   = [];

        foreach ($lineupstatus as $status) {
            $arr_status[] = [
                'status_id' => $status->id,
                'status'    => $status->name,
                'count'     => $this->lineups->where('lineup_status_id', $status->id)->count()
            ];
        }

        return $arr_status;
    }
}
