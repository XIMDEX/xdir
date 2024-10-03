<?php


namespace App\Services;

use Illuminate\Support\Facades\Storage;
use RuntimeException;

class KeyService
{
    public function getPublicKey(): string {
        $publicKeyFile = storage_path('oauth-public.key');
        if (!file_exists($publicKeyFile)) {
          throw new RuntimeException('Public key file not found');
        }
        return file_get_contents($publicKeyFile);
      }
}