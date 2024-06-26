<?php

namespace App\Services;

use App\Builders\PayloadBuilder;
use App\Models\Tool;
use Exception;

class ToolService
{

    private $tool = null;

    private $curlService = null;

    private $payloadBuilder = null;

    public function __construct(Tool $tool, CurlService $curlService, PayloadBuilder $payloadBuilder)
    {
        $this->tool = $tool;
        $this->curlService = $curlService;
        $this->payloadBuilder = $payloadBuilder;

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
            $data = ['user' => $user, 'toolId' => $serviceId];
            $payload = $this->payloadBuilder->setData($data)->setAction('createUser')->build();
            $response = $this->curlService->post($url, $payload);
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


    private function logError($message, $context = [])
    {
        \Log::error($message, $context);
    }
}
