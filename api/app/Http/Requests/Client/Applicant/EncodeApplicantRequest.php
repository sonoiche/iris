<?php

namespace App\Http\Requests\Client\Applicant;

use Illuminate\Foundation\Http\FormRequest;

class EncodeApplicantRequest extends FormRequest
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
            'position_applied'              => 'required',
            'fname'                         => 'required',
            'lname'                         => 'required',
            'birthdate'                     => 'required',
            'keywords'                      => 'required'
        ];
    }

    public function messages()
    {
        return [
            'fname.required'                => 'The first name field is required.',
            'lname.required'                => 'The last name field is required.'
        ];
    }
}
