<?php

namespace Domora\TvGuide\Data;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="person", indexes={@ORM\Index(name="name_index", columns={"name"})})
 */
class Person
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="wikipedia_id", nullable=true)
     * @Serializer\Exclude()
     */
    private $wikipediaId;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Groups({"schedule", "xmltv", "details", "service"})
     * @Serializer\Type("string")
     */
    private $name;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Serializer\Groups({"schedule", "xmltv", "details", "service"})
     * @Serializer\Type("string")
     */
    protected $description;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Serializer\Type("DateTime<'Y-m-d'>")
     * @Serializer\Groups({"schedule", "xmltv", "details", "service"})
     */
    protected $birthDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Groups({"schedule", "xmltv", "details", "service"})
     */
    private $image;

    /**
     * @ORM\Column(type="datetime", name="last_update", nullable=true)
     * @Serializer\Exclude()
     */
    private $lastUpdate;

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
     * @return Person
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
     * @return Person
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
     * Set description
     *
     * @param string $description
     * @return Person
     */
    public function setDescription($description, $locale = null)
    {
        if ($locale) {
            $this->description[$locale] = $description;
        }
        else {
            $this->description = $description;
        }
        
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription($locale = null)
    {
        return $locale ? $this->description[$locale] : $this->description;
    }

    /**
     * Set image
     *
     * @param boolean $image
     *
     * @return Person
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return boolean
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     *
     * @return Person
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * Get lastUpdate
     *
     * @return \DateTime
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return Person
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set wikipediaId
     *
     * @param integer $wikipediaId
     *
     * @return Person
     */
    public function setWikipediaId($wikipediaId)
    {
        $this->wikipediaId = $wikipediaId;

        return $this;
    }

    /**
     * Get wikipediaId
     *
     * @return integer
     */
    public function getWikipediaId()
    {
        return $this->wikipediaId;
    }
}
