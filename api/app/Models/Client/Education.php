<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Education extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "educations";
    protected $guarded = [];
    protected $appends = ['education_level_name','school_year','field_study_name','from_date','to_date'];

    public function education_field()
    {
        return $this->belongsTo(FieldStudy::class, 'field_study');
    }

    public function getEducationLevelNameAttribute()
    {
        $level = isset($this->attributes['education_level']) ? $this->attributes['education_level'] : '';
        if($level) {
            $arrays = config('iris.educational_levels');
            foreach ($arrays as $item) {
                if($item['id'] == $level) {
                    return $item['name'];
                }
            }
        }
    }

    public function getSchoolYearAttribute()
    {
        $duration = isset($this->attributes['duration']) ? $this->attributes['duration'] : '';
        if($duration) {
            $item = explode('-', $duration);
            $from = json_decode($item[0], true);
            $to   = json_decode($item[1], true);

            if(isset($from['month']) && isset($from['year']) && isset($to['month']) && isset($to['year'])) {
                return str_pad($from['month'], 2, '0', STR_PAD_LEFT).'/'.$from['year']. ' - ' .str_pad($to['month'], 2, '0', STR_PAD_LEFT).'/'.$to['year'];
            }
        }

        return '';
    }

    public function getFieldStudyNameAttribute()
    {
        $study = $this->education_field;
        return isset($study->name) ? $study->name : '';
    }

    public function getFromDateAttribute()
    {
        $from = isset($this->attributes['duration']) ? explode('-', $this->attributes['duration']) : '';
        $data = json_decode($from[0], true);
        return [
            'month' => str_pad($data['month'], 2, '0', STR_PAD_LEFT) - 1,
            'year'  => $data['year'] ?? ''
        ];
    }

    public function getToDateAttribute()
    {
        $to = isset($this->attributes['duration']) ? explode('-', $this->attributes['duration']) : '';
        $data = json_decode($to[1], true);
        return [
            'month' => str_pad($data['month'], 2, '0', STR_PAD_LEFT) - 1,
            'year'  => $data['year'] ?? ''
        ];
    }
}
