<?php

namespace Domora\TvGuide\Response;

use JMS\Serializer\Annotation as Serializer;

class Success
{
    /**
     * @Serializer\Type("string")
     * @Serializer\SerializedName("status")
     */
    protected $statusCode;
    
    /**
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("code")
     */
    protected $httpCode;
    
    /**
     * @Serializer\Expose()
     */
    protected $data;
    
    public function __construct($httpCode, $statusCode, $data = null)
    {
        $this->statusCode = $statusCode;
        $this->httpCode = $httpCode;
        $this->data = $data;
    }
}