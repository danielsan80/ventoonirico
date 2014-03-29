<?php

namespace Dan\UserBundle\DataFixtures\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Dan\MainBundle\Entity\Desire;

class LoadDesireData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @todo Incompleted
     */
    public function load(ObjectManager $manager)
    {

        $desire = new Desire($this->getReference('mario'));
        $desire->setGame($this->getReference('agricola'));

        $manager->persist($desire);
        $this->setReference('desire_agricola', $desire);

        $manager->flush();
    }

    public function getOrder()
    {
        return 40;
    }

}
