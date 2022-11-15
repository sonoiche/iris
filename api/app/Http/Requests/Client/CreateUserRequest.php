<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
        $id = $this->input('id');
        return [
            'fname'             => 'required',
            'lname'             => 'required',
            'email'             => 'required|email|unique:users,email,'.(($id) ? $id : null).',id',
            'role_id'           => 'required'
        ];
    }

    public function messages()
    {
        return [
            'fname.required'    => 'The first name field is required.',
            'lname.required'    => 'The last name field is required.',
            'role_id.required'  => 'The role field is required.'
        ];
    }
}
