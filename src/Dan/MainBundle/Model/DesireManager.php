<?php

namespace Dan\MainBundle\Model;
use Dan\MainBundle\Entity\Desire;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Cache\Cache;

class DesireManager
{
    private $entityName = 'DanMainBundle:Desire';
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    private function getRepository()
    {
        return $this->em->getRepository($this->entityName);
    }

    public function getDesires()
    {
        $desires = $this->getRepository()->findAll();
        return $desires;
    }
    
}
