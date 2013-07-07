<?php

namespace Dan\MainBundle\Controller;

use Dan\CommonBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

use Symfony\Component\HttpFoundation\Response;

//use Doctrine\Common\Cache\FilesystemCache;
//use Guzzle\Cache\DoctrineCacheAdapter;
//use Guzzle\Cache\NullCacheAdapter;
//use Guzzle\Plugin\Cache\CachePlugin;

//use Dan\MainBundle\Entity\Game;
use Dan\MainBundle\Entity\Desire;
use Dan\MainBundle\Service\BGGService;

use Symfony\Component\Yaml\Yaml;

/**
 * @Route("") 
 */
class DefaultController extends Controller
{
    
    /**
     * Home page
     * 
     * Route("/", name="home")
     * @return html
     */
    public function indexAction()
    {
        $service = new BGGService($this->get('liip_doctrine_cache.ns.bgg'));
        $games = $service->getGames();
        $games = $service->shiftGames($games);
        return $this->render('DanMainBundle:Default:index.html.twig', array('games' => $games));
    }
    
    /**
     * Home page
     * 
     * @Route("/", name="home")
     * @return html
     */
    public function index2Action()
    {
        return $this->render('DanMainBundle:Default:index2.html.twig', array());
    }
    
}
