<?php

namespace Domora\TvGuide\Data;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Domora\TvGuide\Data\Credits;
use Domora\TvGuide\Service\SluggifyTrait;
use Domora\TvGuide\Service\ImageContentTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="program")
 * @ORM\HasLifecycleCallbacks
 * @Serializer\XmlRoot("programme")
 */
class Program
{
    use SluggifyTrait;
    use ImageContentTrait;
    
    const CATEGORY_UNKNOWN = null;
    const CATEGORY_MOVIE = 'movie';
    const CATEGORY_SERIES = 'series';
    const CATEGORY_DOCUMENTARY = 'documentary';
    const CATEGORY_NEWS = 'news';
    const CATEGORY_SPORT = 'sport';
    const CATEGORY_TVSHOW = 'tvshow';
    const CATEGORY_MUSIC = 'music';
    const CATEGORY_TALKSHOW = 'talkshow';
    const CATEGORY_ANIMATION = 'animation';
    
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=10)
     * @Serializer\Type("string")
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
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Type("string")
     * @Serializer\Groups({"schedule", "xmltv", "details"})
     */
    protected $subtitle;
    
    /**
     * @ORM\Embedded(class="Episode")
     * @Serializer\Type("Domora\TvGuide\Data\Episode")
     * @Serializer\Groups({"xmltv", "details"})
     * @Serializer\Inline()
     */
    protected $episode;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Type("string")
     * @Serializer\Groups({"schedule", "xmltv", "details"})
     */
    protected $category;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Serializer\Type("string")
     * @Serializer\Groups({"schedule", "xmltv", "details"})
     * @Serializer\SerializedName("desc")
     */
    protected $description;
    
    /**
     * @ORM\Embedded(class="Domora\TvGuide\Data\Credits")
     * @Serializer\Type("Domora\TvGuide\Data\Credits")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $credits;

    /**
     * @ORM\Column(type="datetimetz")
     * @Serializer\Type("DateTime")
     * @Serializer\Groups({"schedule", "xmltv", "details"})
     * @Serializer\XmlAttribute()
     */
    protected $start;

    /**
     * @ORM\Column(type="datetimetz")
     * @Serializer\Type("DateTime")
     * @Serializer\Groups({"schedule", "xmltv", "details"})
     * @Serializer\XmlAttribute()
     */
    protected $stop;
    
    public function __toString()
    {
         return sprintf('%s at %s, on %s', $this->title, $this->start->format(\DateTime::ISO8601), $this->channel->getName());
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
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("images")
     * @Serializer\Groups({"schedule", "xmltv", "details"})
     */
    public function getSerializedImages()
    {
        return $this->getImages();
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Generate a unique ID for this program
     * 
     * @ORM\PrePersist
     */
    public function generateUniqueId()
    {
        // Hashes title and subtitle
        $data = $this->sluggify($this->title);
        if ($this->subtitle) $data .= $this->sluggify($this->subtitle);
        
        // Hashes date (without hour info)
        $data .= $this->start->format('YmdHi');
        
        $this->id = substr(sha1($data), 0, 10);
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
    
    /**
     * Set credits
     *
     * @param Credits $credits
     * @return Program
     */
    public function setCredits(Credits $credits)
    {
        $this->credits = $credits;

        return $this;
    }

    /**
     * Get credits
     *
     * @return Credits 
     */
    public function getCredits()
    {
        return $this->credits;
    }
    
    /**
     * Set episode
     *
     * @param Episode $episode
     * @return Program
     */
    public function setEpisode(Episode $episode)
    {
        $this->episode = $episode;

        return $this;
    }

    /**
     * Get episode
     *
     * @return Episode 
     */
    public function getEpisode()
    {
        return $this->episode;
    }
    
    /**
     * Set id
     *
     * @param string $id
     *
     * @return Program
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
