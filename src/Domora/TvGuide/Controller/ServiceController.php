<?php

namespace Domora\TvGuide\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DDesrosiers\SilexAnnotations\Annotations as Silex;

use Domora\TvGuide\Data\Service;

class ServiceController extends AbstractController
{
    const SERVICE_ENTITY = 'Domora\TvGuide\Data\Service';
    
    /**
     * @Silex\Route(
     *     @Silex\Request(method="GET", uri="services")
     * )
     */
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
            
        return $this->serializer->serialize($services, ['list']);
    }
    
    /**
     * @Silex\Route(
     *     @Silex\Request(method="GET", uri="services/{service}"),
     *     @Silex\Convert(variable="service", callback="entity.provider:convertService")
     * )
     */
    public function getServiceAction(Service $service)
    {
        return $this->serializer->serialize($service, ['list']);
    }
}
