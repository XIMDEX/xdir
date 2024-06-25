<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\User;
use App\Services\ToolService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ToolController extends Controller
{
    /**
     *TODO:
     * add get all tools to a service 
     */
    protected $tool;

    protected $toolService;

    public function __construct(Tool $tool, ToolService $toolService)
    {
        $this->tool = $tool;

        $this->toolService = $toolService;

    }

    public function getTools(Request $request)
    {
        $tools = $request->user()->tools;
        return response()->json(['services' => $tools], Response::HTTP_OK);
    }

    public function getList(){
        $tools = $this->tool->all();
        return response()->json(['services' => $tools], Response::HTTP_OK);
    }

    public function createUserOnService(User $user, $serviceId){
        try {
            $this->toolService->createUserOnService($user, $serviceId);
            return response()->json(['message' => 'User created successfully/Found','success' => true], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating user on service','success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
