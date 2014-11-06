<?php

namespace Domora\TvGuide\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\Criteria;

use Domora\TvGuide\Data\Service;

class ServiceController extends AbstractController
{
    const SERVICE_ENTITY = 'Domora\TvGuide\Data\Service';
    
    public function getServicesAction(Request $request)
    {
        $country = $request->get('country');
        $qb = $this->em->createQueryBuilder();
        
        $query = $qb->select('s')
            ->from(self::SERVICE_ENTITY, 's');
            
        if ($country) {
            $query->where('s.country = :country')
                ->setParameter('country', $country);
        }
            
        $services = $query->getQuery()->getResult();
            
        return $this->serializer->response($services, ['list']);
    }
    
    public function getServiceAction(Service $service)
    {
        return $this->serializer->response($service, ['list']);
    }
}
