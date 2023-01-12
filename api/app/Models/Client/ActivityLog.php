<?php

namespace App\Models\Client;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "activity_logs";
    protected $guarded = [];
    protected $appends = ['created_at_display'];

    public function getCreatedAtDisplayAttribute()
    {
        $created_at = $this->attributes['created_at'] ?? '';
        if($created_at) {
            return Carbon::parse($created_at)->format('F d, Y h:i A');
        }

        return '';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
