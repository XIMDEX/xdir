<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssingRoleToUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_uuid' => 'required|exists:users,uuid',
            'role_uuid' => 'required|array|exists:roles,uuid',
            'organization_uuid' => 'required|string|exists:organizations,uuid',
        ];
    }
}
