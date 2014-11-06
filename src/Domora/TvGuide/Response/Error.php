<?php

namespace Domora\TvGuide\Response;

use JMS\Serializer\Annotation as Serializer;

class Error extends \Exception
{
    public $statusCode;
    public $httpCode;
    
    public function __construct($httpCode, $statusCode, \Exception $previous = null)
    {
        $this->statusCode = $statusCode;
        $this->httpCode = $httpCode;
        
        $message = sprintf('API Error %d, "%s"', $httpCode, $statusCode);
        parent::__construct($message, $httpCode, $previous);
    }
}