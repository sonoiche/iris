<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "employments";
    protected $guarded = [];
    protected $appends = ['work_experience','from_date','to_date','country_name'];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function getWorkExperienceAttribute()
    {
        $duration = isset($this->attributes['duration']) ? $this->attributes['duration'] : '';
        if($duration) {
            $item = explode('-', $duration);
            $from = json_decode($item[0], true);
            $to   = json_decode($item[1], true);

            return str_pad($from['month'], 2, '0', STR_PAD_LEFT).'/'.$from['year']. ' - ' .str_pad($to['month'], 2, '0', STR_PAD_LEFT).'/'.$to['year'];
        }

        return '';
    }

    public function getFromDateAttribute()
    {
        $from = isset($this->attributes['duration']) ? explode('-', $this->attributes['duration']) : '';
        $data = json_decode($from[0], true);
        return [
            'month' => str_pad($data['month'], 2, '0', STR_PAD_LEFT) - 1,
            'year'  => $data['year']
        ];
    }

    public function getToDateAttribute()
    {
        $to = isset($this->attributes['duration']) ? explode('-', $this->attributes['duration']) : '';
        $data = json_decode($to[1], true);
        return [
            'month' => str_pad($data['month'], 2, '0', STR_PAD_LEFT) - 1,
            'year'  => $data['year']
        ];
    }

    public function getCountryNameAttribute()
    {
        $country = $this->country;
        return isset($country) ? $country->name : '';
    }
}
