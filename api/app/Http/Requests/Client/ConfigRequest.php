<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class ConfigRequest extends FormRequest
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
            'agency_name'               => 'required_if:type,agency',
            'agency_website'            => 'url',
            'logo'                      => 'sometimes|nullable|mimes:jpg,png,bmp,gif,jpeg',
            'sender_name'               => 'required_if:type,email',
            'sender_email'              => 'required_if:type,email|email'
        ];
    }

    public function messages()
    {
        return [
            'agency_name.required_if'   => 'The agency name field is required.',
            'sender_name.required_if'   => 'The sender name filed is required.',
            'sender_email.required_if'  => 'The sender email field is required.'
        ];
    }
}
