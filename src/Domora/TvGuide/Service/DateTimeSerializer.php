<?php

namespace Domora\TvGuide\Service;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Context;

class DateTimeSerializer implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return array(
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'DateTime',
                'method' => 'serializeDateTime',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'xml',
                'type' => 'DateTime',
                'method' => 'serializeDateTime',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'xml',
                'type' => 'DateTime',
                'method' => 'deserializeXmlDateTime',
            ),
        );
    }

    public function serializeDateTime($visitor, \DateTime $date, array $type, Context $context)
    {
        $groups = $context->attributes->get('groups');
        $isXmlTv = in_array('xmltv', $groups->get());

        // Returns date in the XMLTV format
        if ($isXmlTv) {
            return $date->format('YmdHis O');
        }

        // Returns date in the given format
        if (!empty($type['params'])) {
            return $date->format($type['params'][0]);
        }
        
        // Returns date in the ISO8601 format
        return $date->format(\DateTime::ISO8601);
    }
    
    public function deserializeXmlDateTime($visitor, \SimpleXMLElement $xml, array $params, Context $context)
    {
        $groups = $context->attributes->get('groups');
        $isXmlTv = in_array('xmltv', $groups->get());
        $value = (string) $xml;
        
        return new \DateTime($value);
    }
}
