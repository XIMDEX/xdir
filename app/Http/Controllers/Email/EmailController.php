<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;

class EmailController extends Controller
{
    /**
     * Directory where email templates are stored
     */
    protected $templatesDirectory;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Set the templates directory - adjust this path as needed for your project
        $this->templatesDirectory = resource_path('views/emails');
    }

    /**
     * Send an email using a template
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmail(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'to' => 'required|email',
                'subject' => 'required|string|max:255',
                'template' => 'required|string',
                'cc' => 'nullable|email',
                'bcc' => 'nullable|email',
                'data' => 'nullable|array',
                'attachments' => 'nullable|array',
                'attachments.*.path' => 'required|string',
                'attachments.*.name' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
            }

            // Get validated data
            $to = $request->input('to');
            $subject = $request->input('subject');
            $template = $request->input('template');
            $cc = $request->input('cc');
            $bcc = $request->input('bcc');
            $data = $request->input('data', []);
            $attachments = $request->input('attachments', []);

            // Check if template exists
            if (!$this->templateExists($template)) {
                return response()->json([
                    'error' => 'Email template not found',
                    'template' => $template
                ], Response::HTTP_NOT_FOUND);
            }

            // Send the email
            Mail::send("emails.{$template}", $data, function ($message) use ($to, $subject, $cc, $bcc, $attachments) {
                $message->to($to)->subject($subject);

                // Add CC if provided
                if ($cc) {
                    $message->cc($cc);
                }

                // Add BCC if provided
                if ($bcc) {
                    $message->bcc($bcc);
                }

                // Add attachments if provided
                foreach ($attachments as $attachment) {
                    if (File::exists($attachment['path'])) {
                        $message->attach($attachment['path'], [
                            'as' => $attachment['name']
                        ]);
                    }
                }
            });

            // Check for failures
           // $failures = Mail::failures();
           // if (!empty($failures)) {
           //     Log::error('Email sending failed', ['failures' => $failures]);
           //     return response()->json([
           //         'error' => 'Failed to send email',
           //         'failures' => $failures
           //     ], Response::HTTP_INTERNAL_SERVER_ERROR);
           // }

            return response()->json(['message' => 'Email sent successfully']);
        } catch (\Exception $e) {
            Log::error('Email sending error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while sending the email: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Send bulk emails using a template
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendBulkEmail(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
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
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
            }

            // Get validated data
            $recipients = $request->input('recipients');
            $subject = $request->input('subject');
            $template = $request->input('template');
            $cc = $request->input('cc');
            $bcc = $request->input('bcc');
            $attachments = $request->input('attachments', []);

            // Check if template exists
            if (!$this->templateExists($template)) {
                return response()->json([
                    'error' => 'Email template not found',
                    'template' => $template
                ], Response::HTTP_NOT_FOUND);
            }

            $failures = [];
            $success = [];

            // Send emails to each recipient
            foreach ($recipients as $recipient) {
                $to = $recipient['email'];
                $data = $recipient['data'] ?? [];

                try {
                    Mail::send("emails.{$template}", $data, function ($message) use ($to, $subject, $cc, $bcc, $attachments) {
                        $message->to($to)->subject($subject);

                        // Add CC if provided
                        if ($cc) {
                            $message->cc($cc);
                        }

                        // Add BCC if provided
                        if ($bcc) {
                            $message->bcc($bcc);
                        }

                        // Add attachments if provided
                        foreach ($attachments as $attachment) {
                            if (File::exists($attachment['path'])) {
                                $message->attach($attachment['path'], [
                                    'as' => $attachment['name']
                                ]);
                            }
                        }
                    });

                    // Check for failures
                    $mailFailures = Mail::failures();
                    if (empty($mailFailures)) {
                        $success[] = $to;
                    } else {
                        $failures[] = [
                            'email' => $to,
                            'reason' => 'Mail failure'
                        ];
                    }
                } catch (\Exception $e) {
                    $failures[] = [
                        'email' => $to,
                        'reason' => $e->getMessage()
                    ];
                }
            }

            return response()->json([
                'message' => 'Bulk email process completed',
                'success_count' => count($success),
                'failure_count' => count($failures),
                'failures' => $failures
            ]);
        } catch (\Exception $e) {
            Log::error('Bulk email sending error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while sending bulk emails: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get all available email templates
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTemplates()
    {
        try {
            $templates = $this->getAllTemplates();
            return response()->json(['templates' => $templates]);
        } catch (\Exception $e) {
            Log::error('Error retrieving email templates: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while retrieving email templates'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get a specific email template
     *
     * @param string $template
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTemplate($template)
    {
        try {
            if (!$this->templateExists($template)) {
                return response()->json(['error' => 'Template not found'], Response::HTTP_NOT_FOUND);
            }

            $templatePath = "emails.{$template}";
            $templateContent = $this->getTemplateContent($template);
            $placeholders = $this->extractPlaceholders($templateContent);

            return response()->json([
                'template' => $template,
                'placeholders' => $placeholders
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving email template: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while retrieving the email template'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Check if a template exists
     *
     * @param string $template
     * @return bool
     */
    protected function templateExists($template)
    {
        return View::exists("emails.{$template}");
    }

    /**
     * Get all available templates
     *
     * @return array
     */
    protected function getAllTemplates()
    {
        $templates = [];
        
        if (File::exists($this->templatesDirectory)) {
            $files = File::allFiles($this->templatesDirectory);
            
            foreach ($files as $file) {
                $extension = $file->getExtension();
                if (in_array($extension, ['blade.php', 'php'])) {
                    $filename = $file->getFilenameWithoutExtension();
                    // Remove .blade from filename if present
                    $templateName = str_replace('.blade', '', $filename);
                    
                    $templateContent = $this->getTemplateContent($templateName);
                    $placeholders = $this->extractPlaceholders($templateContent);
                    
                    $templates[] = [
                        'name' => $templateName,
                        'placeholders' => $placeholders
                    ];
                }
            }
        }
        
        return $templates;
    }

    /**
     * Get the content of a template
     *
     * @param string $template
     * @return string
     */
    protected function getTemplateContent($template)
    {
        $templatePath = $this->templatesDirectory . "/{$template}.blade.php";
        if (!File::exists($templatePath)) {
            $templatePath = $this->templatesDirectory . "/{$template}.php";
        }
        
        if (File::exists($templatePath)) {
            return File::get($templatePath);
        }
        
        return '';
    }

    /**
     * Extract placeholders from template content
     * This is a simple implementation that looks for {{ $variable }} patterns
     *
     * @param string $content
     * @return array
     */
    protected function extractPlaceholders($content)
    {
        $placeholders = [];
        preg_match_all('/\{\{\s*\$(\w+)\s*\}\}/', $content, $matches);
        
        if (isset($matches[1]) && !empty($matches[1])) {
            $placeholders = array_unique($matches[1]);
        }
        
        return $placeholders;
    }
}
