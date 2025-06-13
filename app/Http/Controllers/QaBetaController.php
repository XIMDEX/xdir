<?php

namespace App\Http\Controllers;

use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class QaBetaController extends Controller
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
     * Send QA Beta Waitlist Invitation
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendInvitation(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $name = $request->input('name');
            $email = $request->input('email');
            
            // Prepare email data
            $emailData = [
                'name' => $name,
                'supportEmail' => config('mail.support_email', 'support@ximdex.com'),
                'unsubscribeLink' => url('https://front2.cognitrek.ximdex.net/unsubscribe'),
                'privacyPolicyLink' => url('https://front2.cognitrek.ximdex.net/privacy-policy')
            ];

            // Send the email
            $result = $this->emailService->sendEmail(
                $email,
                'Welcome to CogniTrek Beta Program!',
                'qa_beta_waitlist_notification',
                $emailData
            );

            if (!$result['success']) {
                throw new \Exception($result['error'] ?? 'Failed to send email');
            }

            // Log the email to a text file
            $this->logWaitlistEmail($email, $name);

            return response()->json([
                'message' => 'QA beta waitlist invitation sent successfully',
                'email' => $email
            ]);

        } catch (\Exception $e) {
            Log::error('QA Beta Waitlist Invitation Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to send QA beta waitlist invitation',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Log QA Beta Waitlist email to a text file
     * 
     * @param string $email
     * @param string $name
     * @return void
     */
    protected function logWaitlistEmail($email, $name)
    {
        try {
            $logEntry = sprintf(
                "[%s] %s <%s>\n",
                now()->toDateTimeString(),
                $name,
                $email
            );
            
            // Ensure the logs directory exists
            $logDir = storage_path('logs/qa_beta_waitlist');
            if (!file_exists($logDir)) {
                mkdir($logDir, 0755, true);
            }
            
            // Append to the log file (creates if doesn't exist)
            $logFile = $logDir . '/waitlist_emails.log';
            file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
            
        } catch (\Exception $e) {
            Log::error('Failed to log QA beta waitlist email: ' . $e->getMessage());
        }
    }
}
