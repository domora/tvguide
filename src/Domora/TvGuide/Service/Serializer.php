<?php

namespace Domora\TvGuide\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializationContext;

use Domora\TvGuide\Response\Error;

class Serializer
{
    const FORMAT_JSON = 'json';
    const FORMAT_XML = 'xml';

    private $contentTypes = [
        'json' => 'application/json',
        'xml' => 'application/xml'
    ];
    
    public function __construct($serializer)
    {
        $this->serializer = $serializer;
        $this->format = self::FORMAT_JSON;
    }
    
    public function setFormat($format)
    {
        $this->format = $format;
    }

    public function response($data, array $groups = null)
    {
        $context = SerializationContext::create()
            ->setVersion(1);

        if ($groups) {
            $context->setGroups($groups);
        }

        $body = $this->serializer->serialize($data, $this->format, $context);

        if (!$body) {
            return $this->buildResponse("Unable to serialize object", 500);
        }

        return $this->buildResponse($body, 200);
    }
    
    public function deserialize($data, $class, $type)
    {
        return $this->serializer->deserialize($data, $class, $type);
    }

    public function error($message, $code)
    {
        $data = [
            "message" => $message,
            "error" => $code
        ];

        $body = $this->serializer->serialize($data, $this->format);
        
        return $this->buildResponse($body, $code);
    }
    
    public function serialize($data, array $groups = null, $code = 200)
    {
        $context = SerializationContext::create()
            ->setVersion(1);

        if ($groups) {
            $context->setGroups($groups);
        }
        
        if ($data instanceof Error) {
            $code = $data->httpCode;
            $data = [
                "status" => $data->statusCode,
                "code" => $data->httpCode
            ];
        }
        
        $body = $this->serializer->serialize($data, $this->format);
        
        return $this->buildResponse($body, $code);
    }
    
    public function success($http, $status, $data = null)
    {
        $response = [
            "status" => $status,
            "code" => $http,
            "data" => $data
        ];

        $body = $this->serializer->serialize($data, $this->format);
        
        return $this->buildResponse($body, $http);
    }

    private function buildResponse($body, $status)
    {
        $headers = ["Content-Type" => $this->contentTypes[$this->format]];

        return new Response($body, $status, $headers);
    }
}
