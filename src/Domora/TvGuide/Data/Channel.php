<?php

namespace Domora\TvGuide\Data;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="channel")
 * @Serializer\XmlRoot("channel")
 */
class Channel
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     * @Serializer\ReadOnly()
     * @Serializer\Groups({"schedule", "xmltv", "details"})
     * @Serializer\XmlAttribute()
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Serializer\SerializedName("display-name")
     * @Serializer\Groups({"schedule", "xmltv", "details"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Program", mappedBy="channel")
     * @Serializer\Type("array<Domora\TvGuide\Data\Program>")
     * @Serializer\Accessor(getter="getPrograms")
     * @Serializer\Groups({"schedule"})
     * @Serializer\XmlList(entry="program")
     */
    private $programs;

    /**
     * @Serializer\Exclude()
     */
    private static $programsFilter;

    public function __construct()
    {
        $this->programs = new ArrayCollection();
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
     * Set id
     * 
     * @param string id
     * @return Channel
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Channel
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
     * Add programs
     *
     * @param \Domora\TvGuide\Data\Program $programs
     * @return Channel
     */
    public function addProgram(Program $programs)
    {
        $this->programs[] = $programs;

        return $this;
    }

    /**
     * Remove programs
     *
     * @param \Domora\TvGuide\Data\Program $programs
     */
    public function removeProgram(Program $programs)
    {
        $this->programs->removeElement($programs);
    }

    /**
     * Get programs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrograms(Criteria $criteria = null)
    {
        if (!$criteria) $criteria = self::$programsFilter;
        
        return $criteria ? $this->programs->matching(clone $criteria) : $this->programs;
    }

    public static function setProgramsFilter(Criteria $criteria)
    {
        self::$programsFilter = $criteria;
    }
}
