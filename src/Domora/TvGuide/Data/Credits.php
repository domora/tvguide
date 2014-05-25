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
     * @Serializer\XmlList(inline=true, entry="writer")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $writers;
    
    /**
     * @ORM\Column(type="array", nullable=true)
     * @Serializer\Type("array<string>")
     * @Serializer\XmlList(inline=true, entry="actor")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $actors;
    
    /**
     * @ORM\ManyToMany(targetEntity="Person", cascade={"persist"})
     * @ORM\JoinTable(name="program_presenters")
     * @Serializer\Type("array<string>")
     * @Serializer\XmlList(inline=true, entry="presenter")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $presenters;
    
    /**
     * @ORM\Column(type="array", nullable=true)
     * @Serializer\Type("array<string>")
     * @Serializer\XmlList(inline=true, entry="composer")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $composers;
    
    /**
     * @ORM\Column(type="array", nullable=true)
     * @Serializer\Type("array<string>")
     * @Serializer\XmlList(inline=true, entry="guest")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $guests;

    public function __construct()
    {
        $this->presenters = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function addDirector($director)
    {
        $this->directors[] = $director;
    }
    
    public function addWriter($writer)
    {
        $this->writers[] = $writer;
    }
    
    public function addActor($name, $role = null)
    {
        $this->actors[] = $name;
    }
    
    public function addPresenter($presenter)
    {
        $this->presenters[] = $presenter;
    }
    
    public function addComposer($composer)
    {
        $this->composers[] = $composer;
    }
    
    public function addGuest($guest)
    {
        $this->guests[] = $guest;
    }
}
