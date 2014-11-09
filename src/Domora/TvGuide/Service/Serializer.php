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
    
    public function __construct($serializer, $debug)
    {
        $this->serializer = $serializer;
        $this->debug = $debug;
        $this->format = self::FORMAT_JSON;
    }
    
    public function setFormat($format)
    {
        $this->format = $format;
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
    
    public function serialize($data, array $groups = null, $code = 200, $format = self::FORMAT_JSON)
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
                "code" => $data->httpCode,
                "message" => $data->message
            ];
        } elseif ($data instanceof \Exception) {
            $code = 500;
            $exception = $data->getMessage();
            $data = [
                "status" => 'UNKNOWN_ERROR',
                "code" => 500
            ];
            
            if ($this->debug) {
                $data['exception'] = $exception;
            }
        }
        
        $body = $this->serializer->serialize($data, $format, $context);
        
        return $this->buildResponse($body, $code, $format);
    }

    private function buildResponse($body, $status, $format)
    {
        $headers = ["Content-Type" => $this->contentTypes[$format]];

        return new Response($body, $status, $headers);
    }
}
