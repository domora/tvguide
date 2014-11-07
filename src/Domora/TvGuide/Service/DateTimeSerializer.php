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
//            array(
//                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
//                'format' => 'xml',
//                'type' => 'DateTime',
//                'method' => 'serializeDateTime',
//            ),
//            array(
//                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
//                'format' => 'xml',
//                'type' => 'DateTime',
//                'method' => 'deserializeXmlDateTime',
//            ),
            array(
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => 'DateTime',
                'method' => 'deserializeDateTime',
            ),
        );
    }

    public function serializeDateTime($visitor, \DateTime $date, array $type, Context $context)
    {
        $groups = $context->attributes->get('groups');
        
        // Returns date in the XMLTV format
        if (in_array('xmltv', $groups->getOrElse([]))) {
            return $date->format('YmdHis O');
        }

        // Returns date in the given format
        if (!empty($type['params'])) {
            return $date->format($type['params'][0]);
        }
        
        // Returns date in the UNIX format
        return (int) $date->getTimestamp();
    }
    
    public function deserializeDateTime($visitor, $value, array $params, Context $context)
    {
        return \DateTime::createFromFormat('U', $value);
    }
    
    public function deserializeXmlDateTime($visitor, \SimpleXMLElement $xml, array $params, Context $context)
    {
        $groups = $context->attributes->get('groups');
        $isXmlTv = in_array('xmltv', $groups->get());
        $value = (string) $xml;
        
        return new \DateTime($value);
    }
}
