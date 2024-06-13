<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignRoleToUserRequest extends FormRequest
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
            'user_uuid' => 'required|uuid',
            'organizations' => 'required|array',
            'organizations.*.organization_uuid' => 'required|uuid',
            'organizations.*.services' => 'required|array',
            'organizations.*.services.*.service_uuid' => 'required|uuid',
            'organizations.*.services.*.role_uuid' => 'required|array',
            'organizations.*.services.*.role_uuid.*' => 'required',
        ];
    }
}