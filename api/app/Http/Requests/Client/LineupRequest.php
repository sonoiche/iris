<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class LineupRequest extends FormRequest
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
            'status_id'         => 'required',
            'interview_date'    => 'required_if:status_id,3',
            'interview_time'    => 'required_if:status_id,3',
            'venue'             => 'required_if:status_id,3'
        ];
    }

    public function messages()
    {
        return [
            'status_id.required'            => 'The status field is required.',
            'interview_date.required_if'    => 'The interview date field is required.',
            'interview_time.required_if'    => 'The interview time field is required.',
            'venue.required_if'             => 'The interview venue field is required.'
        ];
    }
}
