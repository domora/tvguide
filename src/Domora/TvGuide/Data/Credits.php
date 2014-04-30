<?php

namespace Domora\TvGuide\Data;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Embeddable
 */
class Credits
{
    /**
     * @ORM\Column(type="array", nullable=true)
     * @Serializer\Type("array<string>")
     * @Serializer\XmlList(inline=true, entry="director")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $directors;
    
    /**
     * @ORM\Column(type="array", nullable=true)
     * @Serializer\Type("array<string>")
     * @Serializer\XmlList(inline=true, entry="actor")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $actors;
    
    /**
     * @ORM\Column(type="array", nullable=true)
     * @Serializer\Type("array<string>")
     * @Serializer\XmlList(inline=true, entry="presenter")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $presenters;
    
    //~ /**
     //~ * @Serializer\HandlerCallback("xml", direction="serialization")
     //~ * @Serializer\Groups({"xmltv"})
     //~ */
    //~ public function serializeToXml(\JMS\Serializer\XmlSerializationVisitor $visitor)
    //~ {
        //~ if (empty($this->data)) return null;
        //~ 
        //~ $document = isset($visitor->document) ? $visitor->document : $visitor->createDocument(null, null, true);
        //~ $root = $document->createElement('credits');
        //~ 
        //~ foreach ($this->data as $category => $entries) {
            //~ foreach ($entries as $entry) {
                //~ $node = $document->createElement($category);
                //~ $node->appendChild($document->createTextNode($entry['name']));
                //~ 
                //~ if ($category == self::ACTOR && isset($entry['role'])) {
                    //~ $role = $document->createAttribute("role");
                    //~ $role->appendChild($document->createTextNode($entry['role']));
                    //~ $node->appendChild($role);
                //~ }
                //~ 
                //~ $root->appendChild($node);
            //~ }
        //~ }
//~ 
        //~ return $root;
    //~ }
    
    //~ /**
     //~ * @Serializer\HandlerCallback("xml", direction="deserialization")
     //~ * @Serializer\Groups({"xmltv"})
     //~ */
    //~ public function deserializeFromXml(\JMS\Serializer\XmlDeserializationVisitor $visitor, $data)
    //~ {
        //~ var_dump($data);
        //~ 
        //~ foreach ($data->actor as $key => $value) {
            //~ print $value."<br>";
        //~ }
    //~ }
    
    public function addDirector($director)
    {
        $this->directors[] = $director;
    }
    
    public function addActor($name, $role = null)
    {
        $this->actors[] = $name;
    }
    
    public function addPresenter($presenter)
    {
        $this->presenters[] = $presenter;
    }
}
