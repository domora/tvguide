<?php

namespace Domora\TvGuide\Service;

trait ImageContentTrait
{
    public function hasImages()
    {
        return file_exists(sprintf('%s/%s/%s/original.png', TVGUIDE_IMAGES_DIRECTORY, $this->getImagesDirectoryName(), $this->id));
    }
    
    public function removeImages()
    {
        
    }
    
    public function getImages()
    {
        if (!$this->hasImages()) return null; 
        
        
        return [
            'original' => sprintf("%s/%s/%s/original.png", TVGUIDE_IMAGES_BASE_URI, $this->getImagesDirectoryName(), $this->id),
            'medium' => sprintf("%s/%s/%s/medium.png", TVGUIDE_IMAGES_BASE_URI, $this->getImagesDirectoryName(), $this->id),
            'small' => sprintf("%s/%s/%s/small.png", TVGUIDE_IMAGES_BASE_URI, $this->getImagesDirectoryName(), $this->id),
        ];
    }
    
    private function getImagesDirectoryName()
    {
        $class = new \ReflectionClass($this);
        return strtolower($class->getShortName());
    }
}
