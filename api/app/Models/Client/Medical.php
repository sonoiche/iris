<?php

namespace App\Models\Client;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medical extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "medicals";
    protected $guarded = [];
    protected $appends = ['date_referred_display','date_taken_display','date_result_display','date_expiry_display','clinic_name'];

    public function getDateReferredDisplayAttribute()
    {
        $date = isset($this->attributes['date_referred']) ? $this->attributes['date_referred'] : '';
        return validDate($date) ? Carbon::parse($date)->format('M d, Y'): '';
    }

    public function getDateTakenDisplayAttribute()
    {
        $date = isset($this->attributes['date_taken']) ? $this->attributes['date_taken'] : '';
        return validDate($date) ? Carbon::parse($date)->format('M d, Y'): '';
    }

    public function getDateResultDisplayAttribute()
    {
        $date = isset($this->attributes['date_result']) ? $this->attributes['date_result'] : '';
        return validDate($date) ? Carbon::parse($date)->format('M d, Y'): '';
    }

    public function getDateExpiryDisplayAttribute()
    {
        $date = isset($this->attributes['date_expiry']) ? $this->attributes['date_expiry'] : '';
        return validDate($date) ? Carbon::parse($date)->format('M d, Y'): '';
    }

    public function getClinicNameAttribute()
    {
        $clinic = $this->clinic;
        return isset($clinic->name) ? $clinic->name : '';
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }
}
