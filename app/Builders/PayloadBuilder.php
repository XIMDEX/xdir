<?php

namespace App\Builders;

use App\Builders\interfaces\PayloadBuilderInterface;
use Exception;

class PayloadBuilder implements PayloadBuilderInterface
{
    protected $data;
    protected $action;

    public function setData(array $data): PayloadBuilderInterface
    {
        $this->data = $data;
        return $this;
    }

    public function setAction(string $action): PayloadBuilderInterface
    {
        $this->action = $action;
        return $this;
    }

    /**
     * Returns a JSON-encoded string representation of the payload data and action.
     *
     * @return string The JSON-encoded payload.
     * @throws Exception If there is an error encoding the payload.
     */
    public function build(): array
    {
        $payload = ([
            'data' => $this->data,
            'action' => $this->action,
        ]);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON encode error: ' . json_last_error_msg());
        }

        return $payload;
    }
}
