<?php

namespace Domora\TvGuide\Data;

use JMS\Serializer\Annotation as Serializer;

/**
 * @Serializer\XmlRoot("tv")
 */
class Schedule
{
    /**
     * @Serializer\Exclude()
     */
    private $channels;
    
    public function setChannels(array $channels)
    {
        $this->channels = $channels;
    }
    
    /**
     * @Serializer\Type("array<Domora\TvGuide\Data\Channel>")
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("channels")
     * @Serializer\XmlList(inline=true, entry="channel")
     * @Serializer\Groups({"xmltv"})
     */
    public function getXmlTvChannels()
    {
        return $this->channels;
    }
    
    /**
     * @Serializer\Type("array<Domora\TvGuide\Data\Program>")
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("programs")
     * @Serializer\XmlList(inline=true, entry="programme")
     * @Serializer\Groups({"xmltv"})
     */
    public function getXmlTvPrograms()
    {
        $programs = [];
        
        foreach ($this->channels as $channel) {
            $programs = array_merge($programs, $channel->getPrograms()->toArray());
        }
        
        return $programs;
    }
    
    /**
     * @Serializer\Type("array<Domora\TvGuide\Data\Channel>")
     * @Serializer\VirtualProperty()
     * @Serializer\XmlList(entry="channel")
     * @Serializer\Groups({"schedule"})
     */
    public function getChannels()
    {
        return $this->channels;
    }
}
