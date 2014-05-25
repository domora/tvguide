<?php

namespace Domora\TvGuide\Data;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Domora\TvGuide\Data\Credits;
use Domora\TvGuide\Service\SluggifyTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="program")
 * @ORM\HasLifecycleCallbacks
 * @Serializer\XmlRoot("programme")
 */
class Program
{
    use SluggifyTrait;
    
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
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Type("string")
     * @Serializer\Exclude()
     */
    protected $image;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Serializer\Type("string")
     * @Serializer\Groups({"schedule", "xmltv", "details"})
     * @Serializer\SerializedName("desc")
     */
    protected $description;
    
    ///**
     //* @ORM\Embedded(class="Credits")
     //* @Serializer\Type("Domora\TvGuide\Data\Credits")
     //* @Serializer\Groups({"xmltv", "details"})
     //*/
    //protected $credits;

    /**
     * @ORM\ManyToMany(targetEntity="Person", cascade={"persist"})
     * @ORM\JoinTable(name="program_directors")
     * @Serializer\Exclude()
     */
    protected $directors;
    
    /**
     * @ORM\ManyToMany(targetEntity="Person", cascade={"persist"})
     * @ORM\JoinTable(name="program_writers")
     * @Serializer\Exclude()
     */
    protected $writers;
    
    /**
     * @ORM\ManyToMany(targetEntity="Person", cascade={"persist"})
     * @ORM\JoinTable(name="program_actors")
     * @Serializer\Exclude()
     */
    protected $actors;
    
    /**
     * @ORM\ManyToMany(targetEntity="Person", cascade={"persist"})
     * @ORM\JoinTable(name="program_presenters")
     * @Serializer\Exclude()
     */
    protected $presenters;
    
    /**
     * @ORM\ManyToMany(targetEntity="Person", cascade={"persist"})
     * @ORM\JoinTable(name="program_composers")
     * @Serializer\Exclude()
     */
    protected $composers;
    
    /**
     * @ORM\ManyToMany(targetEntity="Person", cascade={"persist"})
     * @ORM\JoinTable(name="program_guests")
     * @Serializer\Exclude()
     */
    protected $guests;

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

    public function __construct()
    {
        $this->directors = new \Doctrine\Common\Collections\ArrayCollection();
        $this->writers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->actors = new \Doctrine\Common\Collections\ArrayCollection();
        $this->presenters = new \Doctrine\Common\Collections\ArrayCollection();
        $this->composers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->guests = new \Doctrine\Common\Collections\ArrayCollection();
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
        if (!$this->image) return null;
        
        return [
            'original' => sprintf("%s/%s.png", PROGRAMS_IMAGE_URI, $this->id),
            'medium' => sprintf("%s/%s_medium.png", PROGRAMS_IMAGE_URI, $this->id),
            'small' => sprintf("%s/%s_small.png", PROGRAMS_IMAGE_URI, $this->id),
        ];
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
    //public function setCredits(Credits $credits)
    //{
        //$this->credits = $credits;

        //return $this;
    //}

    /**
     * Get credits
     *
     * @return Credits 
     */
    //public function getCredits()
    //{
        //return $this->credits;
    //}

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\Type("array<array<Domora\TvGuide\Data\Person>>")
     * @Serializer\SerializedName("credits")
     * @Serializer\Groups({"xmltv", "details"})
     */
    public function getSerializedCredits()
    {
        $credits = [];
        
        if (count($this->directors)) $credits['directors'] = $this->directors;
        if (count($this->writers)) $credits['writers'] = $this->writers;
        if (count($this->actors)) $credits['actors'] = $this->actors;
        if (count($this->presenters)) $credits['presenters'] = $this->presenters;
        if (count($this->composers)) $credits['composers'] = $this->composers;
        if (count($this->guests)) $credits['guests'] = $this->guests;

        return $credits;
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

    /**
     * Add presenters
     *
     * @param \Domora\TvGuide\Data\Person $presenters
     *
     * @return Program
     */
    public function addPresenter(\Domora\TvGuide\Data\Person $presenters)
    {
        $this->presenters[] = $presenters;

        return $this;
    }

    /**
     * Remove presenters
     *
     * @param \Domora\TvGuide\Data\Person $presenters
     */
    public function removePresenter(\Domora\TvGuide\Data\Person $presenters)
    {
        $this->presenters->removeElement($presenters);
    }

    /**
     * Get presenters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPresenters()
    {
        return $this->presenters;
    }

    /**
     * Add directors
     *
     * @param \Domora\TvGuide\Data\Person $directors
     *
     * @return Program
     */
    public function addDirector(\Domora\TvGuide\Data\Person $directors)
    {
        $this->directors[] = $directors;

        return $this;
    }

    /**
     * Remove directors
     *
     * @param \Domora\TvGuide\Data\Person $directors
     */
    public function removeDirector(\Domora\TvGuide\Data\Person $directors)
    {
        $this->directors->removeElement($directors);
    }

    /**
     * Get directors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDirectors()
    {
        return $this->directors;
    }

    /**
     * Add writers
     *
     * @param \Domora\TvGuide\Data\Person $writers
     *
     * @return Program
     */
    public function addWriter(\Domora\TvGuide\Data\Person $writers)
    {
        $this->writers[] = $writers;

        return $this;
    }

    /**
     * Remove writers
     *
     * @param \Domora\TvGuide\Data\Person $writers
     */
    public function removeWriter(\Domora\TvGuide\Data\Person $writers)
    {
        $this->writers->removeElement($writers);
    }

    /**
     * Get writers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWriters()
    {
        return $this->writers;
    }

    /**
     * Add actors
     *
     * @param \Domora\TvGuide\Data\Person $actors
     *
     * @return Program
     */
    public function addActor(\Domora\TvGuide\Data\Person $actors)
    {
        $this->actors[] = $actors;

        return $this;
    }

    /**
     * Remove actors
     *
     * @param \Domora\TvGuide\Data\Person $actors
     */
    public function removeActor(\Domora\TvGuide\Data\Person $actors)
    {
        $this->actors->removeElement($actors);
    }

    /**
     * Get actors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActors()
    {
        return $this->actors;
    }

    /**
     * Add composers
     *
     * @param \Domora\TvGuide\Data\Person $composers
     *
     * @return Program
     */
    public function addComposer(\Domora\TvGuide\Data\Person $composers)
    {
        $this->composers[] = $composers;

        return $this;
    }

    /**
     * Remove composers
     *
     * @param \Domora\TvGuide\Data\Person $composers
     */
    public function removeComposer(\Domora\TvGuide\Data\Person $composers)
    {
        $this->composers->removeElement($composers);
    }

    /**
     * Get composers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComposers()
    {
        return $this->composers;
    }

    /**
     * Add guests
     *
     * @param \Domora\TvGuide\Data\Person $guests
     *
     * @return Program
     */
    public function addGuest(\Domora\TvGuide\Data\Person $guests)
    {
        $this->guests[] = $guests;

        return $this;
    }

    /**
     * Remove guests
     *
     * @param \Domora\TvGuide\Data\Person $guests
     */
    public function removeGuest(\Domora\TvGuide\Data\Person $guests)
    {
        $this->guests->removeElement($guests);
    }

    /**
     * Get guests
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGuests()
    {
        return $this->guests;
    }
}
