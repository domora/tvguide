<?php

namespace Domora\TvGuide\Service;

use Domora\Tvguide\Data\Person;

class Wikipedia
{
    public function updatePerson(Person $person)
    {
        $uri = 'http://fr.wikipedia.org/w/api.php?format=json&action=query&prop=extracts|pageimages|revisions&exintro=&rvprop=content&rvsection=0&redirects&';

        if ($person->getWikipediaId()) {
            $uri .= 'pageids=' . $person->getWikipediaId();
        }
        else {
            $uri .= 'titles=' . urlencode($person->getName());
        }

        $response = json_decode(file_get_contents($uri), true);
        $responseKeys = array_keys($response['query']['pages']);

        if (sizeof($responseKeys) != 1) {
            throw new \Exception(sprintf('ambiguity with the name "%s"', $person->getName()));
        }
        else if ($responseKeys[0] == -1) {
            return null;
        }

        $result = $response['query']['pages'][$responseKeys[0]];
        $rawContent = strip_tags($result['revisions'][0]['*']);

        $person->setDescription($this->sanitizeDescription($result['extract']), 'fr');
        
        if (isset($result['thumbnail']) && isset($result['thumbnail']['source'])) {
            $person->setImage($this->getImageUrl($result['thumbnail'], 200, 200));
        }

        if (null !== ($birthDate = $this->extractBirthDate($rawContent))) {
            $person->setBirthDate($birthDate);
        }

        if (!$person->getWikipediaId()) {
            $person->setWikipediaId($result['pageid']);
        }
    }

    private function sanitizeDescription($text)
    {
        $text = strip_tags($text, '<p><i><b><sup>');
        $text = preg_replace('/<sup [^>]+>[^>]+<\/sup>/', '', $text);
        $text = preg_replace("/<p><\/p>|\n/", '', $text);

        return trim($text);
    }

    private function sanitizePlaceText($text)
    {
        $text = preg_replace('/\[|\]/', '', $text);

        return trim($text);
    }

    private function extractBirthDate($text)
    {
        $matches = [];
        $months = [
            'janvier', 'février', 'mars', 'avril', 'mai', 'juin',
            'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'
        ];
        
        if (!preg_match('/naissance\s*=\s*\{\{.+\|(\d+)\|(.+)\|(\d+)[^\}]*\}\}/i', $text, $matches)) {
            return null;
        }
        
        if (is_numeric($matches[2])) {
            $month = (int) $matches[2];
        }        
        else if (false === ($month = array_search(strtolower($matches[2]), $months))) {
            throw new \Exception(sprintf('unable to translate the month "%s"', $matches[2]));
        }
        
        $date = new \DateTime();
        $date->setDate($matches[3], $month + 1, $matches[1]);

        return $date;
    }  

    private function getImageUrl($thumbnail, $minWidth, $minHeight)
    {
        if ($thumbnail['width'] < $thumbnail['height']) {
            $finalWidth = round($thumbnail['width'] * $minHeight / $thumbnail['height']);
        }
        else {
            $finalWidth = $minWidth;
        }

        return str_replace($thumbnail['width'].'px', $finalWidth.'px', $thumbnail['source']);
    }
}

