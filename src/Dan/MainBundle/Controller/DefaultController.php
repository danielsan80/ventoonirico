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
     * @return html
     */
    public function indexAction()
    {
        $service = new \Dan\MainBundle\Service\BGGService($this->get('liip_doctrine_cache.ns.bgg'));
        $games = $service->getGames();
        $games = $service->shiftGames($games);
        return $this->render('DanMainBundle:Default:index.html.twig', array('games' => $games));
    }
    
    /**
     * Request 
     * 
     * @Route("/requests", name="request")
     * @Method("POST")
     * 
     * @return json
     */
    public function requestsAction()
    {
        
    }
}
