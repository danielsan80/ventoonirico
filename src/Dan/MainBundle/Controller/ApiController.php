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
//use Dan\MainBundle\Service\BGGService;

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
     * @Route("/desires", name="desire_post")
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
        $desires = $desireRepo->findOneByGameId($gameId);
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
     * @Route("/desires/{gameId}", name="desire_get")
     * @Method("GET")
     * 
     * @return json
     */
    public function getDesireAction($gameId)
    {

        $user = $this->get('user');
        
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $desireRepo = $em->getRepository('DanMainBundle:Desire');
        $desires = $desireRepo->findOneByGameId($gameId);
        
        $response = new Response();
        if ($desires) {
            $desire = $desires[0];
            $response->setContent($desire->getAsJson());
        } else {
            $response->setContent(json_encode(array('game_id' => $gameId)));
        }

        return $response;
    }
    
    /**
     * Request 
     * 
     * @Route("/desires/{gameId}", name="desire_put")
     * @Method("PUT")
     * 
     * @return json
     */
    public function putDesireAction($gameId)
    {

        $user = $this->get('user');
        
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $desireRepo = $em->getRepository('DanMainBundle:Desire');
        $desires = $desireRepo->findOneByGameId($gameId);
        
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
