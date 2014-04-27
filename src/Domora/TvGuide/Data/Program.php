<?php

namespace Domora\TvGuide\Data;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="program")
 */
class Program
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Serializer\Groups({"list", "details"})
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Channel", inversedBy="programs")
     * @Serializer\Exclude()
     */
    protected $channel;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Groups({"list", "details"})
     */
    protected $name;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Groups({"list", "details"})
     */
    protected $description;

    /**
     * @ORM\Column(type="datetimetz")
     * @Serializer\Groups({"list", "details"})
     */
    protected $start;

    /**
     * @ORM\Column(type="datetimetz")
     * @Serializer\Groups({"list", "details"})
     */
    protected $end;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Program
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     * @return Program
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     * @return Program
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime 
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set channel
     *
     * @param \Domora\TvGuide\Data\Channel $channel
     * @return Program
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

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("channel")
     * @Serializer\Groups({"list", "details"})
     */
    public function getSerializedChannel()
    {
        return $this->channel->getId();
    }
}
