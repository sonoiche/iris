<?php

namespace App\Models\Client;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class License extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "licenses";
    protected $guarded = [];
    protected $appends = ['date_issue_display','date_taken_display','date_expiry_display'];

    public function setDateIssueAttribute($value)
    {
        $this->attributes['date_issue'] = null;
        if(isset($value)) {
            $this->attributes['date_issue'] = Carbon::parse($value)->addDay()->format('Y-m-d');
        }
    }

    public function setDateTakenAttribute($value)
    {
        $this->attributes['date_taken'] = null;
        if(isset($value)) {
            $this->attributes['date_taken'] = Carbon::parse($value)->addDay()->format('Y-m-d');
        }
    }

    public function setDateExpiryAttribute($value)
    {
        $this->attributes['date_expiry'] = null;
        if(isset($value)) {
            $this->attributes['date_expiry'] = Carbon::parse($value)->addDay()->format('Y-m-d');
        }
    }

    public function getDateIssueDisplayAttribute()
    {
        $date = $this->attributes['date_issue'];
        return isset($date) ? Carbon::parse($date)->format('d M, Y') : '';
    }

    public function getDateTakenDisplayAttribute()
    {
        $date = $this->attributes['date_taken'];
        return isset($date) ? Carbon::parse($date)->format('d M, Y') : '';
    }

    public function getDateExpiryDisplayAttribute()
    {
        $date = $this->attributes['date_expiry'];
        return isset($date) ? Carbon::parse($date)->format('d M, Y') : '';
    }

    public function getDateIssueAttribute()
    {
        $date = $this->attributes['date_issue'];
        return isset($date) ? Carbon::parse($date)->format('m/d/Y') : '';
    }

    public function getDateTakenAttribute()
    {
        $date = $this->attributes['date_taken'];
        return isset($date) ? Carbon::parse($date)->format('m/d/Y') : '';
    }

    public function getDateExpiryAttribute()
    {
        $date = $this->attributes['date_expiry'];
        return isset($date) ? Carbon::parse($date)->format('m/d/Y') : '';
    }
}
