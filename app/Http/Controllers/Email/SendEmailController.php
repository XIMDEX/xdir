<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Http\Requests\Email\SendEmailRequest;
use App\Http\Requests\Email\SendBulkEmailRequest;
use App\Services\EmailService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SendEmailController extends Controller
{
    /**
     * Email service
     */
    protected $emailService;

    /**
     * Constructor
     */
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Send an email using a template
     *
     * @param SendEmailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmail(SendEmailRequest $request)
    {
        try {

            // Get validated data as an array
            $emailData = $request->only(['to', 'subject', 'template', 'cc', 'bcc']);
            $emailData['data'] = $request->input('data', []);
            $emailData['attachments'] = $request->input('attachments', []);

            // Send the email using the service
            $result = $this->emailService->sendEmail(
                $emailData['to'], 
                $emailData['subject'], 
                $emailData['template'], 
                $emailData['data'], 
                $emailData['cc'] ?? null, 
                $emailData['bcc'] ?? null, 
                $emailData['attachments']
            );

            if (!$result['success']) {
                return response()->json([
                    'error' => $result['error'],
                    'failures' => $result['failures'] ?? null
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json(['message' => 'Email sent successfully']);
        } catch (\Exception $e) {
            Log::error('Email sending error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while sending the email: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Send bulk emails using a template
     *
     * @param SendBulkEmailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendBulkEmail(SendBulkEmailRequest $request)
    {
        try {

            // Get validated data as an array
            $emailData = $request->only(['recipients', 'subject', 'template', 'cc', 'bcc']);
            $emailData['attachments'] = $request->input('attachments', []);

            // Send bulk emails using the service
            $result = $this->emailService->sendBulkEmail(
                $emailData['recipients'],
                $emailData['subject'],
                $emailData['template'],
                $emailData['cc'] ?? null,
                $emailData['bcc'] ?? null,
                $emailData['attachments']
            );

            if (!$result['success']) {
                return response()->json([
                    'error' => $result['error']
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json([
                'message' => $result['message'],
                'success_count' => $result['success_count'],
                'failure_count' => $result['failure_count'],
                'failures' => $result['failures']
            ]);
        } catch (\Exception $e) {
            Log::error('Bulk email sending error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while sending bulk emails: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
