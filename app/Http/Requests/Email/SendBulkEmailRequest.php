<?php

namespace App\Http\Requests\Email;

use Illuminate\Foundation\Http\FormRequest;

class SendBulkEmailRequest extends FormRequest
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
            'recipients' => 'required|array',
            'recipients.*.email' => 'required|email',
            'recipients.*.data' => 'nullable|array',
            'subject' => 'required|string|max:255',
            'template' => 'required|string',
            'cc' => 'nullable|email',
            'bcc' => 'nullable|email',
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
            'recipients.required' => 'The recipients list is required',
            'recipients.array' => 'The recipients must be provided as an array',
            'recipients.*.email.required' => 'Each recipient must have an email address',
            'recipients.*.email.email' => 'Each recipient email must be a valid email address',
            'subject.required' => 'Email subject is required',
            'template.required' => 'Email template name is required',
        ];
    }
}
