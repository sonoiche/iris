<?php

namespace App\Http\Requests\Employer;

use Illuminate\Foundation\Http\FormRequest;

class JobOrderRequest extends FormRequest
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
            'principal_id'          => 'required',
            'date_needed'           => 'required',
            'date_receive'          => 'required',
            'status'                => 'required',
            'job_type'              => 'required'
        ];
    }

    public function messages()
    {
        return [
            'principal_id.required' => 'The principal field is required.',
            'job_type.required'     => 'The job order type field is required.'
        ];
    }
}
