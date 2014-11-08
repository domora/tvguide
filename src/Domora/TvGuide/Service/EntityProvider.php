<?php

namespace Domora\TvGuide\Service;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Domora\TvGuide\Response\Error;

class EntityProvider
{
    private $em;
    
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function convertChannel($channelId)
    {
        $channel = $this->em->find('Domora\TvGuide\Data\Channel', $channelId);

        if (!$channel) {
            throw new Error(404, 'CHANNEL_NOT_FOUND');
        }
        
        return $channel;
    }
    
    public function convertProgram($programId)
    {
        $program = $this->em->find('Domora\TvGuide\Data\Program', $programId);

        if (!$program) {
            throw new Error(404, 'PROGRAM_NOT_FOUND');
        }
        
        return $program;
    }
    
    public function convertService($serviceId)
    {
        $service = $this->em->find('Domora\TvGuide\Data\Service', $serviceId);

        if (!$service) {
            throw new Error(404, 'SERVICE_NOT_FOUND');
        }
        
        return $service;
    }
}
