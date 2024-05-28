<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendInviteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|'/**unique:users,email */,
            'organization' => 'required|exists:organizations,uuid'
        ];
    }

    protected function prepareForValidation()
    {
        // Merge URL parameters into the request data
        $this->merge([
            'email' => $this->route('email'),
            'organization' => $this->route('organization')
        ]);
    }
}
