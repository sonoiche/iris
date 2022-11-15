<?php

namespace App\Models\Client;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Training extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "trainings";
    protected $guarded = [];
    protected $appends = ['date_issue_display','date_expiry_display'];

    public function getDateIssueDisplayAttribute()
    {
        $date = isset($this->attributes['date_issue']) ? $this->attributes['date_issue'] : '';
        return validDate($date) ? Carbon::parse($date)->format('M d, Y'): '';
    }

    public function getDateExpiryDisplayAttribute()
    {
        $date = isset($this->attributes['date_expiry']) ? $this->attributes['date_expiry'] : '';
        return validDate($date) ? Carbon::parse($date)->format('M d, Y'): '';
    }
}
