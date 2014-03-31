<?php

namespace Dan\MainBundle\DataFixtures\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Dan\MainBundle\Entity\Join;

class LoadJoinData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @todo Incompleted
     */
    public function load(ObjectManager $manager)
    {

        $join = new Join(
                $this->getReference('desire_agricola'),
                $this->getReference('luigi')
            );

        $manager->persist($join);
        $this->setReference('desire_agricola', $join);

        $manager->flush();
    }

    public function getOrder()
    {
        return 50;
    }

}
