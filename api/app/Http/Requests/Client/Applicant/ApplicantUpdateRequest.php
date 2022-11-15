<?php

namespace App\Http\Requests\Client\Applicant;

use Illuminate\Foundation\Http\FormRequest;

class ApplicantUpdateRequest extends FormRequest
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
            'source_id'             => 'required',
            'date_applied'          => 'required',
            'fname'                 => 'required',
            'lname'                 => 'required',
            'mobile_number'         => 'required',
            'birthdate'             => 'required'
        ];
    }

    public function messages()
    {
        return [
            'source_id.required'    => 'The source field is requried.'
        ];
    }
}
