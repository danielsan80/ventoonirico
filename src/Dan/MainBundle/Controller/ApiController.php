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

/**
 * @Route("/api") 
 */
class ApiController extends Controller
{
    
    
    /**
     * Request 
     * 
     * @Route("/user", name="user")
     * @Method("GET")
     * 
     * @return json
     */
    public function getUserAction()
    {
        $user = $this->get('user');
        $response = new Response();
        if ($user) {
            $response->setContent($user->getAsJson());
        } else {
            $response->setContent('{}');
        }

        return $response;
    }
    
    /**
     * Request 
     * 
     * @Route("/games/{id}", name="get_game")
     * @Method("GET")
     * 
     * @return json
     */
    public function getGameAction($id)
    {
        $user = $this->get('user');
        
        $service = new BGGService($this->get('liip_doctrine_cache.ns.bgg'));
        $game = $service->getGame($id);
        $game = $game->getAsArray();
        
        $em = $this->getDoctrine()->getEntityManager();
        $desireRepo = $em->getRepository('DanMainBundle:Desire');
        $desires = $desireRepo->findByGameId($id);
        
        $response = new Response();
        if ($desires) {
            $desire = $desires[0];
            $game['desire'] = $desire->getAsArray();
        }
        $response->setContent(json_encode($game));

        return $response;
    }
    
    /**
     * Request 
     * 
     * @Route("/desires", name="post_desire")
     * @Method("POST")
     * 
     * @return json
     */
    public function postDesireAction()
    {

        $user = $this->get('user');
        
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $desireRepo = $em->getRepository('DanMainBundle:Desire')->setUser($user);
        $gameId = json_decode($request->getContent())->game_id;
        $desires = $desireRepo->findByGameId($gameId);
        $desire = $desires[0];
        
        if (!$desire) {
            $desire = new Desire($user);
            $desire->setGameId($gameId);
            $em->persist($desire);
            $em->flush();
        }

        $response = new Response();
        $response->setContent($desire->getAsJson());

        return $response;
    }
    
    /**
     * Request 
     * 
     * @Route("/desires/{id}", name="get_desire")
     * @Method("GET")
     * 
     * @return json
     */
    public function getDesireAction($id)
    {

        $user = $this->get('user');
        
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $desireRepo = $em->getRepository('DanMainBundle:Desire');
        $desires = $desireRepo->find($id);
        
        $response = new Response();
        if ($desires) {
            $response->setContent($desire->getAsJson());
        } else {
            $response->setStatusCode('404');
        }

        return $response;
    }
    
    /**
     * Request 
     * 
     * @Route("/desires/{id}", name="desire_put")
     * @Method("PUT")
     * 
     * @return json
     */
    public function putDesireAction($id)
    {

        $user = $this->get('user');
        
        $request = $this->getRequest();
        $gameId = $request->get('gameId');
        $em = $this->getDoctrine()->getEntityManager();
        $desireRepo = $em->getRepository('DanMainBundle:Desire');
        $desires = $desireRepo->find($id);
        
        $response = new Response();
        if ($desires) {
            $desire = $desires[0];
        } else {
            $desire = new Desire($user);
            $desire->setGameId($gameId);
            $em->persist($desire);
        }
        
        $em->flush();
        
        $response->setContent($desire->getAsJson());
        return $response;
    }
}
