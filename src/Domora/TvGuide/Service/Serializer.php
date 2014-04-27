<?php

namespace Domora\TvGuide\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializationContext;

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
    }

    public function response($data, array $groups = null)
    {
        $context = SerializationContext::create()
            ->setVersion(1);

        if ($groups) {
            $context->setGroups($groups);
        }

        $format = self::FORMAT_JSON;

        $body = $this->serializer->serialize($data, $format, $context);

        if (!$body) {
            return $this->buildResponse("Unable to serialize object", 500, $format);
        }

        return $this->buildResponse($body, 200, $format);
    }

    public function error($message, $code)
    {
        $data = [
            "message" => $message,
            "error" => $code
        ];

        $format = self::FORMAT_JSON;
        $body = $this->serializer->serialize($data, $format);
        
        return $this->buildResponse($body, $code, $format);
    }

    private function buildResponse($body, $status, $format)
    {
        $headers = ["Content-Type" => $this->contentTypes[$format]];

        return new Response($body, $status, $headers);
    }
}
