<?php

namespace Domora\TvGuide\Response;

class Success
{
    private $responseCode;
    private $httpCode;
    
    public function __construct($httpCode, $responseCode)
    {
        $this->responseCode = $responseCode;
        $this->httpCode = $httpCode;
    }
}