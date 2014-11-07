<?php

namespace Domora\TvGuide\Response;

use JMS\Serializer\Annotation as Serializer;

class Error extends \Exception
{
    public $statusCode;
    public $httpCode;
    public $message;
    
    public function __construct($httpCode, $statusCode, $message = null, \Exception $previous = null)
    {
        $this->statusCode = $statusCode;
        $this->httpCode = $httpCode;

        if (!$message) {
            $message = sprintf('API Error %d, "%s"', $httpCode, $statusCode);
        }
        
        parent::__construct($message, $httpCode, $previous);
    }
}