<?php

namespace Domora\TvGuide\Data;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="credits")
 */
class Credits
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Exclude()
     */
    protected $id;
    
    /**
     * @ORM\ManyToMany(targetEntity="Person", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="program_directors")
     * @Serializer\Type("ArrayCollection<Domora\TvGuide\Data\Person>")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $directors;
    
    /**
     * @ORM\ManyToMany(targetEntity="Person", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="program_writers")
     * @Serializer\Type("ArrayCollection<Domora\TvGuide\Data\Person>")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $writers;
    
    /**
     * @ORM\ManyToMany(targetEntity="Person", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="program_actors")
     * @Serializer\Expose()
     * @Serializer\Type("ArrayCollection<Domora\TvGuide\Data\Person>")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $actors;
    
    /**
     * @ORM\ManyToMany(targetEntity="Person", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="program_presenters")
     * @Serializer\Type("ArrayCollection<Domora\TvGuide\Data\Person>")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $presenters;
    
    /**
     * @ORM\ManyToMany(targetEntity="Person", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="program_composers")
     * @Serializer\Type("ArrayCollection<Domora\TvGuide\Data\Person>")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $composers;
    
    /**
     * @ORM\ManyToMany(targetEntity="Person", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="program_guests")
     * @Serializer\Type("ArrayCollection<Domora\TvGuide\Data\Person>")
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
    
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Add presenters
     *
     * @param Person $presenters
     *
     * @return Program
     */
    public function addPresenter(Person $presenters)
    {
        $this->presenters[] = $presenters;

        return $this;
    }

    /**
     * Remove presenters
     *
     * @param Person $presenters
     */
    public function removePresenter(Person $presenters)
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
     * Replace presenters
     *
     * @param Closure $c
     */
    public function replacePresenters(\Closure $c)
    {
        if ($this->presenters) {
            $this->presenters = $this->presenters->map($c);
        }
    }

    /**
     * Add directors
     *
     * @param Person $directors
     *
     * @return Program
     */
    public function addDirector(Person $directors)
    {
        $this->directors[] = $directors;

        return $this;
    }

    /**
     * Remove directors
     *
     * @param Person $directors
     */
    public function removeDirector(Person $directors)
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
     * Replace directors
     *
     * @param Closure $c
     */
    public function replaceDirectors(\Closure $c)
    {
        if ($this->directors) {
            $this->directors = $this->directors->map($c);
        }
    }

    /**
     * Add writers
     *
     * @param Person $writers
     *
     * @return Program
     */
    public function addWriter(Person $writers)
    {
        $this->writers[] = $writers;

        return $this;
    }

    /**
     * Remove writers
     *
     * @param Person $writers
     */
    public function removeWriter(Person $writers)
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
     * Replace writers
     *
     * @param Closure $c
     */
    public function replaceWriters(\Closure $c)
    {
        if ($this->writers) {
            $this->writers = $this->writers->map($c);
        }
    }

    /**
     * Add actors
     *
     * @param Person $actors
     *
     * @return Program
     */
    public function addActor(Person $actors)
    {
        $this->actors[] = $actors;

        return $this;
    }

    /**
     * Remove actors
     *
     * @param Person $actors
     */
    public function removeActor(Person $actors)
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
     * Replace actors
     *
     * @param Closure $c
     */
    public function replaceActors(\Closure $c)
    {
        if ($this->actors) {
            $this->actors = $this->actors->map($c);
        }
    }

    /**
     * Add composers
     *
     * @param Person $composers
     *
     * @return Program
     */
    public function addComposer(Person $composers)
    {
        $this->composers[] = $composers;

        return $this;
    }

    /**
     * Remove composers
     *
     * @param Person $composers
     */
    public function removeComposer(Person $composers)
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
     * Replace composers
     *
     * @param Closure $c
     */
    public function replaceComposers(\Closure $c)
    {
        if ($this->composers) {
            $this->composers = $this->composers->map($c);
        }
    }

    /**
     * Add guests
     *
     * @param Person $guests
     *
     * @return Program
     */
    public function addGuest(Person $guests)
    {
        $this->guests[] = $guests;

        return $this;
    }

    /**
     * Remove guests
     *
     * @param Person $guests
     */
    public function removeGuest(Person $guests)
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
    
    /**
     * Replace guests
     *
     * @param Closure $c
     */
    public function replaceGuests(\Closure $c)
    {
        if ($this->guests) {
            $this->guests = $this->guests->map($c);
        }
    }
}
