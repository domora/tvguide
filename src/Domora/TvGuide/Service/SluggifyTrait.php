<?php

namespace Domora\TvGuide\Service;

trait SluggifyTrait
{
    function sluggify($text)
    {
        // Replaces non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        
        // Transliterates
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        
        // Lowercase
        $text = strtolower($text);
        
        // Removes unwanted characters
        return preg_replace('~[^-\w]+~', '', $text);
    }
}
