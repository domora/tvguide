<?php

namespace Domora\TvGuide\Data;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="credit")
 */
class Credit
{
    const DIRECTOR = 'director';
    const ACTOR = 'actor';
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Serializer\Groups({"schedule", "details"})
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Program", inversedBy="credits")
     */
    protected $program;
    
    /**
     * @ORM\Column(type="string")
     * @Serializer\Groups({"schedule", "details"})
     */
    protected $role;
    
    /**
     * @ORM\Column(type="string")
     * @Serializer\Groups({"schedule", "details"})
     */
    protected $name;
    
    /**
     * @Serializer\HandlerCallback("xml", direction="serialization")
     * @Serializer\Groups({"xmltv"})
     */
    public function serializeToXml(\JMS\Serializer\XmlSerializationVisitor $visitor)
    {
        $document = isset($visitor->document) ? $visitor->document : $visitor->createDocument(null, null, true);
        $element = $document->createElement($this->role);
        $element->appendChild($document->createTextNode($this->name));
        
        return $element;
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Credits
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Credits
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set program
     *
     * @param \Domora\TvGuide\Data\Program $program
     * @return Credit
     */
    public function setProgram(\Domora\TvGuide\Data\Program $program = null)
    {
        $this->program = $program;

        return $this;
    }

    /**
     * Get program
     *
     * @return \Domora\TvGuide\Data\Program 
     */
    public function getProgram()
    {
        return $this->program;
    }
}
