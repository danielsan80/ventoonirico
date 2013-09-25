<?php

namespace Dan\UserBundle\DataFixtures\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Dan\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @todo Incompleted
     */
    public function load(ObjectManager $manager)
    {

        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@admin.it');
        $user->setPlainPassword('admin');
        $user->setEnabled(true);
        $user->setConfirmationToken(null);
        $user->addGroup($this->getReference('superadmin'));

        $manager->persist($user);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }

}
