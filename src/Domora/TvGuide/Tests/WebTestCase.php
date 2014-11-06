<?php

namespace Domora\TvGuide\Tests;

class WebTestCase extends \Silex\WebTestCase
{
    public function createApplication()
    {
        return new \Domora\TvGuide\Application();
    }
}