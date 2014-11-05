<?php

namespace Domora\TvGuide\Controller;

use Doctrine\ORM\EntityManager;
use Domora\TvGuide\Service\Serializer;

abstract class AbstractController
{
    public function __construct(EntityManager $em, Serializer $serializer)
    {
        $this->em = $em;
        $this->serializer = $serializer;
    }
}
