<?php

namespace Domora\TvGuide\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\Criteria;

use Domora\TvGuide\Data\Channel;
use Domora\TvGuide\Data\Program;
use Domora\TvGuide\Response\Success;

class ChannelController extends AbstractController
{
    const CHANNEL_ENTITY = 'Domora\TvGuide\Data\Channel';
    
    public function getChannelsAction(Request $request)
    {
        $country = $request->get('country');
        $qb = $this->em->createQueryBuilder();
        
        $query = $qb->select('c')
            ->from(self::CHANNEL_ENTITY, 'c');
            
        if ($country) {
            $query->where('c.country = :country')
                ->setParameter('country', $country);
        }
            
        $channels = $query->getQuery()->getResult();
            
        return $this->serializer->response($channels, ['details']);
    }
    
    public function getChannelAction(Channel $channel)
    {
        return $this->serializer->response($channel, ['details']);
    }
    
    // @todo handle error cases
    public function postChannelProgramsAction(Request $request, Channel $channel)
    {
        $program = $this->serializer->deserialize($request->getContent(), ProgramController::PROGRAM_ENTITY, 'json');
        $program->setChannel($channel);
        $channel->addProgram($program);
        $program->generateUniqueId();
        $this->em->persist($program);
        
        $response = new Success(200, 'PROGRAM_CREATED', $program);
        
        return $this->serializer->response($response);
    }
}
