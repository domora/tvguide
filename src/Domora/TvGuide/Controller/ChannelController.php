<?php

namespace Domora\TvGuide\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\DeserializationContext;
use Doctrine\Common\Collections\Criteria;

use Domora\TvGuide\Data\Program;
use Domora\TvGuide\Data\Channel;
use Domora\TvGuide\Data\Schedule;

class ChannelController implements ControllerProviderInterface
{
    const CHANNEL_ENTITY = 'Domora\TvGuide\Data\Channel';
    
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        
        $this->em = $app['orm.em'];
        $this->serializer = $app['api.serializer'];
        
        $provider = $app['entity.provider']
            ->getProvider(self::CHANNEL_ENTITY);
        
        $controllers->get('/channels', array($this, 'getChannels'));
        $controllers->get('/channels/{channel}', array($this, 'getChannel'))
            ->convert('channel', $provider);

        return $controllers;
    }
    
    public function getChannels(Request $request)
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
    
    public function getChannel(Channel $channel)
    {
        return $this->serializer->response($channel, ['details']);
    }
}
