<?php

namespace Dan\MainBundle\DataFixtures\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Dan\MainBundle\Entity\Game;

class LoadGameData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @todo Incompleted
     */
    public function load(ObjectManager $manager)
    {

        $game = new Game();
        $game->setBggId('31260');
        $game->setName('Agricola');
        $game->setOwners(array('Ventoonirico','mario'));
        $game->setThumbnail('http://cf.geekdo-images.com/images/pic259085_t.jpg');
        $game->setMinPlayers(1);
        $game->setMaxPlayers(5);

        $manager->persist($game);
        $this->setReference('agricola', $game);

        $manager->flush();
    }

    public function getOrder()
    {
        return 30;
    }

}
