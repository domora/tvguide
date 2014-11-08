<?php

namespace Domora\TvGuide\Controller;

use Doctrine\ORM\EntityManager;
use Domora\TvGuide\Service\Serializer;
use Domora\TvGuide\Application;

abstract class AbstractController
{
    public function __construct(Application $app)
    {
        $this->em = $app['orm.em'];
        $this->serializer = $app['api.serializer'];
    }
}
