<?php

namespace Domora\TvGuide\Service;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntityProviderFactory
{
    private $em;
    
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function getProvider($entityName)
    {
        return function($id) use($entityName) {
            return $this->convert($entityName, $id);
        };
    }
    
    public function convert($entityName, $id)
    {
        $entity = $this->em->find($entityName, $id);

        if (!$entity) {
            throw new NotFoundHttpException('unable to find the requested program');
        }
        
        return $entity;
    }
}
