<?php

namespace Domora\TvGuide\Data;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="service_channel")
 */
class ServiceChannelAssociation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Service")
     */
    protected $service;
    
    /**
     * @ORM\ManyToOne(targetEntity="Channel")
     * @Serializer\Groups({"service"})
     * @Serializer\Inline()
     */
    protected $channel;
    
    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"service"})
     */
    protected $number;

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
     * Set number
     *
     * @param integer $number
     *
     * @return ServiceChannelAssociation
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set service
     *
     * @param \Domora\TvGuide\Data\Service $service
     *
     * @return ServiceChannelAssociation
     */
    public function setService(\Domora\TvGuide\Data\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Domora\TvGuide\Data\Service 
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set channel
     *
     * @param \Domora\TvGuide\Data\Channel $channel
     *
     * @return ServiceChannelAssociation
     */
    public function setChannel(\Domora\TvGuide\Data\Channel $channel = null)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return \Domora\TvGuide\Data\Channel 
     */
    public function getChannel()
    {
        return $this->channel;
    }
}
