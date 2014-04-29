<?php

namespace Domora\TvGuide\Data;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Embeddable
 */
class Credits
{
    const DIRECTOR = 'director';
    const ACTOR = 'actor';
    
    /**
     * @ORM\Column(type="array")
     * @Serializer\Exclude()
     */
    protected $data;
    
    /**
     * @Serializer\HandlerCallback("xml", direction="serialization")
     * @Serializer\Groups({"xmltv"})
     */
    public function serializeToXml(\JMS\Serializer\XmlSerializationVisitor $visitor)
    {
        $document = isset($visitor->document) ? $visitor->document : $visitor->createDocument(null, null, true);
        $root = $document->createElement('credits');
        
        foreach ($this->data as $entry) {
            list($role, $name) = $entry;
            $node = $document->createElement($role);
            $node->appendChild($document->createTextNode($name));
            $root->appendChild($node);
        }

        return $root;
    }
    
    public function add($role, $name)
    {
        $this->data[] = [$role, $name];
    }
    
    
}
