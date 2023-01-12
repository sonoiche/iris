<?php

namespace App\Models\Client;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Applicant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "applicants";
    protected $guarded = [];
    protected $appends = [
        'display_photo',
        'fullname',
        'birthdate_display',
        'source_name',
        'nationality_name',
        'keywords_display',
        'age_gender',
        'applicant_name',
        'date_applied_display',
        'latest_employment',
        'updated_at_display',
        'position_applied',
        'yrs_of_exp',
        'passport_date_submitted',
        'passport_date_issued',
        'passport_date_expiry'
    ];

    public function source()
    {
        return $this->hasOne(ApplicantSource::class, 'id', 'source_id');
    }

    public function nationality()
    {
        return $this->hasOne(Nationality::class, 'id', 'nationality_id');
    }

    public function position()
    {
        return $this->hasOne(ApplicantPosition::class, 'applicant_id', 'applicant_number');
    }

    public function educations()
    {
        return $this->hasMany(Education::class, 'applicant_id', 'applicant_number');
    }

    public function lineup()
    {
        return $this->hasOne(Lineup::class, 'applicant_id', 'applicant_number');
    }

    public function getDisplayPhotoAttribute()
    {
        $photo = isset($this->attributes['photo']) ? $this->attributes['photo'] : '';
        if($photo) {
            return config('app.api_url').'/storage/'.$photo;
        }

        return config('app.api_url').'/images/no-photo.png';
    }

    public function getFullnameAttribute()
    {
        $fname = isset($this->attributes['fname']) ? $this->attributes['fname'] : '';
        $mname = isset($this->attributes['mname']) ? ' '.$this->attributes['mname'] : '';
        $lname = $this->attributes['lname'] ?? '';

        return $lname.', '.$fname.$mname;
    }

    public function getBirthdateDisplayAttribute()
    {
        $date = isset($this->attributes['birthdate']) ? $this->attributes['birthdate'] : '';
        if($date) {
            return Carbon::parse($date)->format('d M, Y');
        }
        return '';
    }

    public function getUpdatedAtDisplayAttribute()
    {
        $date = isset($this->attributes['updated_at']) ? $this->attributes['updated_at'] : '';
        if($date) {
            return Carbon::parse($date)->format('d M, Y');
        }
        return '';
    }

    public function getSourceNameAttribute()
    {
        return isset($this->source) ? $this->source->name : '';
    }

    public function getNationalityNameAttribute()
    {
        return isset($this->nationality) ? $this->nationality->name : '';
    }

    public function getKeywordsDisplayAttribute()
    {
        $keywords = isset($this->attributes['keywords']) ? str_replace('],[', ',', $this->attributes['keywords']) : '';
        $keywords = json_decode($keywords, true) ?? [];
        $content  = '';
        if(count($keywords)) {
            foreach ($keywords as $keyword) {
                $content .= $keyword['name'].', ';
            }
        }

        return substr($content, 0, -2);
    }

    public function getAgeGenderAttribute()
    {
        $birthdate = $this->attributes['birthdate'] ?? '';
        $gender    = $this->attributes['gender'] ?? '';

        if($birthdate && $gender) {
            return Carbon::parse($birthdate)->age.'/'.$gender;
        } else if($birthdate && !$gender) {
            return Carbon::parse($birthdate)->age;
        } else if(!$birthdate && $gender) {
            return $gender;
        } else {
            return '';
        }
    }

    public function getApplicantNameAttribute()
    {
        $fname = isset($this->attributes['fname']) ? $this->attributes['fname'] : '';
        $mname = isset($this->attributes['mname']) ? ' '.$this->attributes['mname'] : '';
        $lname = $this->attributes['lname'] ?? '';
        $applicant_number = $this->attributes['applicant_number'];

        return $lname.', '.$fname.$mname.'<br>'.$applicant_number;
    }

    public function getDateAppliedDisplayAttribute()
    {
        $date = $this->attributes['date_applied'] ?? '';
        if($date) {
            return Carbon::parse($date)->format('d M, Y');
        }
        return '';
    }

    public function getKeywordsAttribute()
    {
        $keywords = $this->attributes['keywords'] ?? '';
        if($keywords) {
            $content = [];
            $results = explode(',', $keywords);
            foreach ($results as $result) {
                $content[] = [
                    'id'    => $result,
                    'name'  => $result
                ];
            }

            return json_encode($content);
        }

        return '';
    }

    public function getLatestEmploymentAttribute()
    {
        return '';
    }

    public function getPositionAppliedAttribute()
    {
        return $this->position->position_applied ?? '';
    }

    public function getYrsOfExpAttribute()
    {
        return $this->position->years_of_experience ?? '';
    }

    public function getPassportDateSubmittedAttribute()
    {
        return $this->passport_date_submitted ?? '';
    }

    public function getPassportDateIssuedAttribute()
    {
        return $this->passport_date_issued ?? '';
    }

    public function getPassportDateExpiryAttribute()
    {
        return $this->passport_date_expiry ?? '';
    }
}
