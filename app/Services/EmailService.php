<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;

class EmailService
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
     * @param string $to
     * @param string $subject
     * @param string $template
     * @param array $data
     * @param string|null $cc
     * @param string|null $bcc
     * @param array $attachments
     * @return array
     */
    public function sendEmail($to, $subject, $template, array $data = [], $cc = null, $bcc = null, array $attachments = [])
    {
        try {
            // Check if template exists
            if (!$this->templateExists($template)) {
                return [
                    'success' => false,
                    'error' => 'Email template not found',
                    'template' => $template
                ];
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
           //     return [
           //         'success' => false,
           //         'error' => 'Failed to send email',
           //         'failures' => $failures
           //     ];
           // }

            return [
                'success' => true,
                'message' => 'Email sent successfully'
            ];
        } catch (\Exception $e) {
            Log::error('Email sending error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'An error occurred while sending the email: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send bulk emails using a template
     *
     * @param array $recipients
     * @param string $subject
     * @param string $template
     * @param string|null $cc
     * @param string|null $bcc
     * @param array $attachments
     * @return array
     */
    public function sendBulkEmail(array $recipients, $subject, $template, $cc = null, $bcc = null, array $attachments = [])
    {
        try {
            // Check if template exists
            if (!$this->templateExists($template)) {
                return [
                    'success' => false,
                    'error' => 'Email template not found',
                    'template' => $template
                ];
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

            return [
                'success' => true,
                'message' => 'Bulk email process completed',
                'success_count' => count($success),
                'failure_count' => count($failures),
                'failures' => $failures
            ];
        } catch (\Exception $e) {
            Log::error('Bulk email sending error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'An error occurred while sending bulk emails: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get all available email templates
     *
     * @return array
     */
    public function getAllTemplates()
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
     * Get a specific template with its placeholders
     *
     * @param string $template
     * @return array|null
     */
    public function getTemplate($template)
    {
        if (!$this->templateExists($template)) {
            return null;
        }

        $templateContent = $this->getTemplateContent($template);
        $placeholders = $this->extractPlaceholders($templateContent);

        return [
            'template' => $template,
            'placeholders' => $placeholders
        ];
    }

    /**
     * Check if a template exists
     *
     * @param string $template
     * @return bool
     */
    public function templateExists($template)
    {
        return View::exists("emails.{$template}");
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
