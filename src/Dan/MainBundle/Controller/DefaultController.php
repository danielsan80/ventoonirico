<?php

namespace Dan\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

use Doctrine\Common\Cache\FilesystemCache;
use Guzzle\Cache\DoctrineCacheAdapter;
use Guzzle\Cache\NullCacheAdapter;
use Guzzle\Plugin\Cache\CachePlugin;

use Dan\MainBundle\Entity\Game;

/**
 * @Route("") 
 */
class DefaultController extends Controller
{
    
    /**
     * Home page
     * 
     * @Route("/", name="home")
     * @Cache(expires="+1 hour")
     * @return html
     */
    public function indexAction()
    {
        $guzzle = $this->get('guzzle');
        
//        $adapter = new DoctrineCacheAdapter(new FilesystemCache(__DIR__.'/../../../../app/cache'));
//        $cache = new CachePlugin($adapter, true);
//        $guzzle->addSubscriber($cache);
        
        $games = array();
        $users = array(
            'ventoonirico' => 'Ventoonirico',
            'danielsan80' => 'Danilo',
            'mcevoli' => 'Marco',
            'rotilio' => 'Rollo',
            'f4br1z10' => 'Fabri',
        );
        $service = $guzzle->getService('BGGService');
        
        foreach($users as $username => $name) {
            $xml = $service->execute('collection', array('username' => $username));
            $xml = new \SimpleXMLElement($xml);

            $items = $xml->xpath('/items/item');

            foreach($items as $item) {
                $game = new Game($item, array('owner' => $name));
                if (isset($games[$game->getId()])) {
                    $games[$game->getId()]->addOwner($name);
                } else {
                    $games[$game->getId()] = $game;
                }
            }
        }
        
        $now = new \DateTime();
        $year = $now->format('Y') - 2010;
        $week = $now->format('W') + ($year*54);
        $offset = $week % count($games);
        $slice = array_slice($games, $offset, null, true);
        $games = array_slice($games, 0, $offset, true);
        $games = array_merge($slice, $games);
        
        return $this->render('DanMainBundle:Default:index.html.twig', array('games' => $games));
    }
}
