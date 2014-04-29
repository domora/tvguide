<?php

namespace Domora\TvGuide\Data;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="program")
 * @Serializer\XmlRoot("programme")
 */
class Program
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Serializer\Type("integer")
     * @Serializer\Groups({"schedule", "details"})
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Channel", inversedBy="programs")
     * @Serializer\Exclude()
     */
    protected $channel;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Type("string")
     * @Serializer\Groups({"schedule", "xmltv", "details"})
     */
    protected $title;
    
    /**
     * @ORM\Column(type="string")
     * @Serializer\Type("string")
     * @Serializer\Groups({"schedule", "xmltv", "details"})
     */
    protected $subtitle;
    
    /**
     * @ORM\Column(type="string")
     * @Serializer\Type("string")
     * @Serializer\Groups({"schedule", "xmltv", "details"})
     */
    protected $episode;
    
    /**
     * @ORM\Column(type="string")
     * @Serializer\Type("string")
     * @Serializer\Groups({"schedule", "xmltv", "details"})
     */
    protected $category;
    
    /**
     * @ORM\Column(type="string")
     * @Serializer\Type("string")
     * @Serializer\Groups({"schedule", "xmltv", "details"})
     */
    protected $image;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Type("string")
     * @Serializer\Groups({"schedule", "xmltv", "details"})
     * @Serializer\SerializedName("desc")
     */
    protected $description;
    
    /**
     * @ORM\OneToMany(targetEntity="Credit", mappedBy="program")
     * @Serializer\Type("array<Credit>")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $credits;

    /**
     * @ORM\Column(type="datetimetz")
     * @Serializer\Type("DateTime<'YmdHis O'>")
     * @Serializer\Groups({"schedule", "xmltv", "details"})
     * @Serializer\XmlAttribute()
     */
    protected $start;

    /**
     * @ORM\Column(type="datetimetz")
     * @Serializer\Type("DateTime<'YmdHis O'>")
     * @Serializer\Groups({"schedule", "xmltv", "details"})
     * @Serializer\XmlAttribute()
     */
    protected $stop;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->credits = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("channel")
     * @Serializer\Groups({"xmltv", "details"})
     * @Serializer\XmlAttribute()
     */
    public function getSerializedChannel()
    {
        return $this->channel->getId();
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
     * Set category
     *
     * @param string $category
     * @return Program
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Program
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set stop
     *
     * @param \DateTime $stop
     * @return Program
     */
    public function setStop($stop)
    {
        $this->stop = $stop;

        return $this;
    }

    /**
     * Get stop
     *
     * @return \DateTime 
     */
    public function getStop()
    {
        return $this->stop;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     * @return Program
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string 
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set episode
     *
     * @param string $episode
     * @return Program
     */
    public function setEpisode($episode)
    {
        $this->episode = $episode;

        return $this;
    }

    /**
     * Get episode
     *
     * @return string 
     */
    public function getEpisode()
    {
        return $this->episode;
    }

    /**
     * Add credits
     *
     * @param \Domora\TvGuide\Data\Credit $credits
     * @return Program
     */
    public function addCredit(\Domora\TvGuide\Data\Credit $credits)
    {
        $this->credits[] = $credits;

        return $this;
    }

    /**
     * Remove credits
     *
     * @param \Domora\TvGuide\Data\Credit $credits
     */
    public function removeCredit(\Domora\TvGuide\Data\Credit $credits)
    {
        $this->credits->removeElement($credits);
    }
    
    /**
     * Get credits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCredits()
    {
        return $this->credits;
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
     * Set title
     *
     * @param string $title
     * @return Program
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }
}
