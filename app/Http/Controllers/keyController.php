<?php

namespace App\Http\Controllers;

use App\Services\KeyService;
use Illuminate\Http\Request;

class keyController extends Controller
{
    private $keyService;

    public function __construct(KeyService $keyService) {
      $this->keyService = $keyService;
    }
  
    public function getPublicKey() {
        $publicKeyFile = storage_path('oauth-public.key');
        return response()->download($publicKeyFile, 'oauth-public.key', ['Content-Type' => 'application/x-pem-file']);
    }
}
