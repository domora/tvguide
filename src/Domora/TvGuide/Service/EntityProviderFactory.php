<?php

namespace Domora\TvGuide\Service;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Domora\TvGuide\Error\Error;

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
            throw new Error(404, 'ENTITY_NOT_FOUND');
        }
        
        return $entity;
    }
}
