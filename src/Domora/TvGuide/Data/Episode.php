<?php

namespace Domora\TvGuide\Data;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Embeddable
 */
class Episode
{
    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Serializer\Type("integer")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $season;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Serializer\Type("integer")
     * @Serializer\Groups({"xmltv", "details"})
     */
    protected $episode;
    
    public function getSeason()
    {
        return $this->season;
    }
    
    public function setSeason($season)
    {
        $this->season = $season;
        
        return $this;
    }
    
    public function getEpisode()
    {
        return $this->episode;
    }
    
    public function setEpisode($episode)
    {
        $this->episode = $episode;
        
        return $this;
    }
}
