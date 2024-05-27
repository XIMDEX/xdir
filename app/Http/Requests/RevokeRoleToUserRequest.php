<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\User;
use App\Models\Role;

class RevokeRoleToUserRequest extends FormRequest
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
            'role_uuid' => 'required|exists:roles,uuid',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $user = User::where('uuid', $this->user_uuid)->first();
            $role = Role::where('uuid', $this->role_uuid)->first();

            if ($user && $role && !$user->hasRole($role->name)) {
                $validator->errors()->add('role_uuid', 'User does not have the specified role');
            }
        });
    }
}
