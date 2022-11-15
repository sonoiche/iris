<?php

namespace App\Http\Requests\Client\Applicant;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
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
            'name'              => 'required',
            'document_type_id'  => 'required',
            'attachment'        => 'required_if:type,create'
        ];
    }
}
