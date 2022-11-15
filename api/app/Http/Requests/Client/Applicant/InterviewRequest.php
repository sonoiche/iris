<?php

namespace App\Http\Requests\Client\Applicant;

use Illuminate\Foundation\Http\FormRequest;

class InterviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'applicant_ids'         => 'required',
            'principal_id'          => 'required',
            'position_id'           => 'required',
            'interview_date'        => 'required',
            'venue'                 => 'required'
        ];
    }
}
