<?php

namespace App\Models\Client\Employer;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Client\Country;
use App\Models\Client\Industry;
use App\Models\Client\Employer\Contact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Principal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "principals";
    protected $guarded = [];
    protected $appends = [
        'date_issue_display',
        'date_expiry_display',
        'industry_name',
        'country_name',
        'assigned_user_list',
        'accreditation_details',
        'contact_person',
        'contact_details',
        'logo_display',
        'logo_link'
    ];

    public function industry()
    {
        return $this->belongsTo(Industry::class, 'industry_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'principal_id');
    }

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

    public function getIndustryNameAttribute()
    {
        return isset($this->industry) ? $this->industry->name : '';
    }

    public function getCountryNameAttribute()
    {
        return isset($this->country) ? $this->country->name : '';
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

    public function getAccreditationDetailsAttribute()
    {
        $date_issue = isset($this->attributes['date_issue']) ? 'Date Issue: '.Carbon::parse($this->attributes['date_issue'])->format('M d, Y').'<br>' : '';
        $date_expiry = isset($this->attributes['date_expiry']) ? 'Date Expiry: '.Carbon::parse($this->attributes['date_expiry'])->format('M d, Y').'<br>' : '';
        $accreditation_number = isset($this->attributes['accreditation_number']) ? 'Accreditation Number: '.$this->attributes['accreditation_number'] : '';

        return $date_issue.$date_expiry.$accreditation_number;
    }

    public function getContactPersonAttribute()
    {
        $contact = $this->contacts->where('main_contact', 1)->first();
        $contact_number = isset($contact->mobile_number) ? '<br><span class="fa fa-mobile fa-fw"></span> '.$contact->mobile_number : '';
        $email = isset($contact->email) ? '<br><i class="fa fa-at fa-fw"></i> '.$contact->email : '';

        return '<span class="fa fa-user fa-fw"></span> '.$contact->name.$contact_number.$email;
    }

    public function getContactDetailsAttribute()
    {
        $landline = isset($this->attributes['landline']) ? '<span class="fa fa-phone fa-fw"></span> '.$this->attributes['landline'].'<br>' : '';
        $mobile_number = isset($this->attributes['mobile_number']) ? '<span class="fa fa-mobile fa-fw"></span> '.$this->attributes['mobile_number'] : '';

        return $landline.$mobile_number;
    }

    public function getLogoDisplayAttribute()
    {
        $logo = isset($this->attributes['logo']) ? $this->attributes['logo'] : '';
        return basename($logo);
    }

    public function getLogoLinkAttribute()
    {
        $logo = isset($this->attributes['logo']) ? $this->attributes['logo'] : '';
        return config('app.api_url').'/storage/'.$logo;
    }
}
