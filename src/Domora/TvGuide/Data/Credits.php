<?php

namespace Domora\TvGuide\Data;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Embeddable
 */
class Credits
{
    /**
     * @ORM\ManyToMany(targetEntity="Person", cascade={"persist"})
     * @ORM\JoinTable(name="program_directors")
     * @Serializer\Type("array<Domora\TvGuide\Data\Person>")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $directors;
    
    /**
     * @ORM\ManyToMany(targetEntity="Person", cascade={"persist"})
     * @ORM\JoinTable(name="program_writers")
     * @Serializer\Type("array<Domora\TvGuide\Data\Person>")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $writers;
    
    /**
     * @ORM\ManyToMany(targetEntity="Person", cascade={"persist"})
     * @ORM\JoinTable(name="program_actors")
     * @Serializer\Type("array<Domora\TvGuide\Data\Person>")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $actors;
    
    /**
     * @ORM\ManyToMany(targetEntity="Person", cascade={"persist"})
     * @ORM\JoinTable(name="program_presenters")
     * @Serializer\Type("array<Domora\TvGuide\Data\Person>")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $presenters;
    
    /**
     * @ORM\ManyToMany(targetEntity="Person", cascade={"persist"})
     * @ORM\JoinTable(name="program_composers")
     * @Serializer\Type("array<Domora\TvGuide\Data\Person>")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $composers;
    
    /**
     * @ORM\ManyToMany(targetEntity="Person", cascade={"persist"})
     * @ORM\JoinTable(name="program_guests")
     * @Serializer\Type("array<Domora\TvGuide\Data\Person>")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $guests;

    public function __construct()
    {
        $this->directors = new ArrayCollection();
        $this->writers = new ArrayCollection();
        $this->actors = new ArrayCollection();
        $this->presenters = new ArrayCollection();
        $this->composers = new ArrayCollection();
        $this->guests = new ArrayCollection();
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
