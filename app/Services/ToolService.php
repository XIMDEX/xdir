<?php

namespace App\Services;

use App\Models\Tool;
use Exception;

class ToolService
{

    private $tool = null;

    public function __construct(Tool $tool)
    {
        $this->tool = $tool;
    }

    public function createUserOnService($user, $serviceId)
    {
        try {
            $toolService = $this->tool->find($serviceId);
            if (!$toolService) {
                throw new Exception('Tool service not found');
            }
    
            $serviceUrl = $toolService->url;
            $url = $serviceUrl . '/xdir?XDEBUG_SESSION_START';
            $payload = $this->preparePayload($user, $serviceId);
    
            $response = $this->sendRequest($url, $payload);
            $data = json_decode($response);
    
            if (!$response || !$data || !$data->success) {
                $this->logError('Error creating user on service', [
                    'user' => $user->id,
                    'service' => $serviceId,
                    'response' => $response,
                ]);
                throw new Exception('Error creating user on service');
            }
    
            return true;
        } catch (\Exception $e) {
            $this->logError($e->getMessage(), [
                'user' => $user->id,
                'service' => $serviceId,
            ]);
            throw $e;
        }
    }
    

    private function preparePayload($user, $serviceId)
    {
        $payload = json_encode([
            'data' => ['user' => $user, 'toolId' => $serviceId],
            'action' => 'createUser'
        ]);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON encode error: ' . json_last_error_msg());
        }

        return $payload;
    }

    private function sendRequest($url, $payload)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);

        return $response;
    }

    private function logError($message, $context = [])
    {
        \Log::error($message, $context);
    }
}
