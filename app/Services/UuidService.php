<?php

namespace App\Services;

use Ramsey\Uuid\Uuid;

class UuidService
{
    public function generateUuid(): string
    {
        return Uuid::uuid4()->toString();
    }
}