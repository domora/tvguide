<?php

namespace Domora\TvGuide\Response;

class Error extends \Exception
{
    public function __construct($httpResponseCode, $errorCode, \Exception $previous = null)
    {
        $this->code = $errorCode;
        $message = sprintf('API Error %d, "%s"', $httpResponseCode, $errorCode);
        parent::__construct($message, $httpResponseCode, $previous);
    }
}