<?php

namespace Tripay\Exceptions;

class DefaultTripayException extends \Exception
{
    public function getMessageAsJson()
    {
        return json_encode([
            'success' => false,
            'message' => $this->getMessage(),
        ]);
    }
}
