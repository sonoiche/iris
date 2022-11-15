<?php

namespace App\Http\Requests\Client\Applicant;

use Illuminate\Foundation\Http\FormRequest;

class LicenseRequest extends FormRequest
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
            'title'             => 'required',
            'license_number'    => 'required'
        ];
    }

    public function messages()
    {
        return [
            'title.required'    => 'The certificate or license name field is required.'
        ];
    }
}
