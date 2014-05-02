<?php

namespace Domora\TvGuide\Data;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="service")
 */
class Service
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     * @Serializer\Groups({"list", "service"})
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Groups({"list", "service"})
     */
    private $name;
    
    /**
     * @ORM\Column(type="string")
     * @Serializer\Groups({"list", "service"})
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity="ServiceChannelAssociation", mappedBy="service")
     * @Serializer\Type("array<Domora\TvGuide\Data\ServiceChannelAssociation>")
     * @Serializer\Groups({"service"})
     */
    private $channels;

    public function __construct()
    {
        $this->channels = new ArrayCollection();
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
     * Set country
     *
     * @param string $country
     * @return Channel
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Add channels
     *
     * @param \Domora\TvGuide\Data\ServiceChannelAssociation $channels
     *
     * @return Service
     */
    public function addChannel(\Domora\TvGuide\Data\ServiceChannelAssociation $channels)
    {
        $this->channels[] = $channels;

        return $this;
    }

    /**
     * Remove channels
     *
     * @param \Domora\TvGuide\Data\ServiceChannelAssociation $channels
     */
    public function removeChannel(\Domora\TvGuide\Data\ServiceChannelAssociation $channels)
    {
        $this->channels->removeElement($channels);
    }

    /**
     * Get channels
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChannels()
    {
        return $this->channels;
    }
}
