<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ToolController extends Controller
{
    protected $tool;

    public function __construct(Tool $tool)
    {
        $this->tool = $tool;
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
}
