<?php

namespace App\Builders\interfaces;
interface PayloadBuilderInterface
{
    public function setData(array $data): PayloadBuilderInterface;
    public function setAction(string $action): PayloadBuilderInterface;
    public function build(): array;
}