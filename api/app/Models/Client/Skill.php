<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skill extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "skills";
    protected $guarded = [];
    protected $appends = ['skill_level_name'];

    public function getSkillLevelNameAttribute()
    {
        $level = isset($this->attributes['skill_level']) ? $this->attributes['skill_level'] : '';
        if($level) {
            $arrays = config('iris.skill_levels');
            foreach ($arrays as $item) {
                if($item['id'] == $level) {
                    return $item['name'];
                }
            }
        }
    }
}
