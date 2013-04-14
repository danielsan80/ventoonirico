<?php

namespace Dan\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
     * @return html
     */
    public function indexAction()
    {
        $guzzle = $this->get('guzzle');
        
//        $adapter = new DoctrineCacheAdapter(new FilesystemCache(__DIR__.'/../../../../app/cache'));
//        $cache = new CachePlugin($adapter, true);
//        $guzzle->addSubscriber($cache);
        
        $service = $guzzle->getService('BGGService');
        $games = $service->execute('collection', array('username' => 'ventoonirico'));
        $xml = new \SimpleXMLElement($games);
        
        $items = $xml->xpath('/items/item');
        
        $games = array();
        foreach($items as $item) {
            $games[] = new Game($item, array('owner' => 'ventoonirico'));
        }
        
        return $this->render('DanMainBundle:Default:index.html.twig', array('games' => $games));
    }
}
