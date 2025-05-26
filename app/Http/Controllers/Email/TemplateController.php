<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class TemplateController extends Controller
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
     * Get all available email templates
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTemplates()
    {
        try {
            $templates = $this->emailService->getAllTemplates();
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
            $templateData = $this->emailService->getTemplate($template);
            
            if (!$templateData) {
                return response()->json(['error' => 'Template not found'], Response::HTTP_NOT_FOUND);
            }

            return response()->json($templateData);
        } catch (\Exception $e) {
            Log::error('Error retrieving email template: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while retrieving the email template'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
