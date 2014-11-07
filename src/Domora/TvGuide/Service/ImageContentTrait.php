<?php

namespace Domora\TvGuide\Service;

trait ImageContentTrait
{
    public static $imagesDirectory;
    public static $imagesBaseUri;
    
    public function hasImages()
    {
        return file_exists(sprintf('%s/%s/%s/original.png', self::$imagesDirectory, $this->getImagesDirectoryName(), $this->id));
    }
    
    public function removeImages()
    {
        
    }
    
    public function getImages()
    {
        if (!$this->hasImages()) return null; 
        
        
        return [
            'original' => sprintf("%s/%s/%s/original.png", self::$imagesBaseUri, $this->getImagesDirectoryName(), $this->id),
            'medium' => sprintf("%s/%s/%s/medium.png", self::$imagesBaseUri, $this->getImagesDirectoryName(), $this->id),
            'small' => sprintf("%s/%s/%s/small.png", self::$imagesBaseUri, $this->getImagesDirectoryName(), $this->id),
        ];
    }
    
    public function importImageFromUrl($url)
    {
        $resizeImage = function($image, $max_width, $max_height) {
            $orig_width = imagesx($image);
            $orig_height = imagesy($image);
            $width = $orig_width;
            $height = $orig_height;

            // Taller
            if ($height > $max_height) {
                $width = ($max_height / $height) * $width;
                $height = $max_height;
            }

            // Wider
            if ($width > $max_width) {
                $height = ($max_width / $width) * $height;
                $width = $max_width;
            }

            $image_p = imagecreatetruecolor($width, $height);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);

            return $image_p;
        };
        
        $directory = sprintf('%s/%s/%s', self::$imagesDirectory, $this->getImagesDirectoryName(), $this->id);
        
        // Create the program directory if necessary
        if (!is_dir($directory)) {
            mkdir($directory);
        }
        
        $image = imagecreatefromjpeg($url);
        imagepng($image, "$directory/original.png", 9);
        
        $medium = $resizeImage($image, 300, 300);
        imagepng($medium, "$directory/medium.png", 7);
        
        $small = $resizeImage($image, 200, 200);
        imagepng($small, "$directory/small.png", 7);
    }
    
    private function getImagesDirectoryName()
    {
        $class = new \ReflectionClass($this);
        return strtolower($class->getShortName());
    }
}
