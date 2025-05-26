<?php

namespace App\Http\Requests\Email;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // You can add authorization logic here if needed
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'template' => 'required|string',
            'cc' => 'nullable|email',
            'bcc' => 'nullable|email',
            'data' => 'nullable|array',
            'attachments' => 'nullable|array',
            'attachments.*.path' => 'required|string',
            'attachments.*.name' => 'required|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'to.required' => 'The recipient email address is required',
            'to.email' => 'The recipient must be a valid email address',
            'subject.required' => 'Email subject is required',
            'template.required' => 'Email template name is required',
        ];
    }
}
