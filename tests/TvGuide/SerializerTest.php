<?php

namespace Domora\Tests\TvGuide;

use JMS\Serializer\DeserializationContext;

use Domora\Tests\WebTestCase;

class SerializerTest extends WebTestCase
{
    public function testDeserializeXmlTv()
    {
        $data = file_get_contents(__DIR__ . '/../Data/xmltv-file1.xml');
        $context = DeserializationContext::create()->setGroups(['xmltv']);
        $program = $this->app['serializer']->deserialize($data, 'Domora\TvGuide\Data\Program', 'xml', $context);
        
        $this->assertEquals("Esprits criminels", $program->getTitle());
        $this->assertEquals(1398636900, $program->getStart()->getTimestamp());
        $this->assertEquals(1398639900, $program->getStop()->getTimestamp());
    }
}