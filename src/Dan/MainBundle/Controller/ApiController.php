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
        $serializer = $this->get('jms_serializer');

        $user = $this->get('user');
        $manager = $this->get('model.manager.user');
        $user = $manager->findUserBy(array('id' => $user->getId()));
        $response = new Response($this->serialize($user), 200, array('Content-Type' => 'application/json'));

        return $response;
    }

    /**
     * Request 
     * 
     * @Route("/games", name="get_games")
     * @Method("GET")
     * 
     * @return json
     */
    public function getGamesAction()
    {
        if ($this->getRequest()->query->get('filter')=='desired') {
            return $this->forward('DanMainBundle:Api:getDesiredGames');
        }
        $manager = $this->get('model.manager.game');
        $serializer = $this->get('serializer');
        $games = $manager->getAllGames();

        $games = $manager->shiftGames($games);

        $result = $serializer->serialize($games, 'json');

        $response = new Response();
        $response->setContent($result);

        return $response;
    }
    
    /**
     * Request 
     * 
     * @return json
     */
    public function getDesiredGamesAction()
    {
        $manager = $this->get('model.manager.game');
        $serializer = $this->get('serializer');
        $games = $manager->getDesiredGames();

        $result = $serializer->serialize($games, 'json');

        $response = new Response();
        $response->setContent($result);

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
        $desire = $this->deserialize('Dan\MainBundle\Entity\Desire', $request);

        $em->persist($desire);
        $em->flush($desire);

        $response = new Response();
        $response->setContent($this->serialize($desire));
        $response->headers->set('ContentType', 'application/json');

        return $response;
    }

    /**
     * Request 
     * 
     * @Route("/joins", name="post_join")
     * @Method("POST")
     * 
     * @return json
     */
    public function postJoinAction()
    {

        $user = $this->get('user');

        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $joinRepo = $em->getRepository('DanMainBundle:Join');
        $join = $this->deserialize('Dan\MainBundle\Entity\Join', $request);

        $em->persist($join);
        $em->flush($join);

        $response = new Response();
        $response->setContent($this->serialize($join));
        $response->headers->set('ContentType', 'application/json');

        return $response;
    }

    /**
     * Request 
     * 
     * @Route("/joins/{id}", name="delete_join")
     * @Method("DELETE")
     * 
     * @return json
     */
    public function deleteJoinAction($id)
    {

        $user = $this->get('user');

        $em = $this->getDoctrine()->getEntityManager();
        $joinRepo = $em->getRepository('DanMainBundle:Join');
        $join = $joinRepo->findOneById($id);
        if ($join) {
            if ($join->getUser()->getId() == $user->getId()) {
                $em->remove($join);
                $em->flush();
                return new Response('', 200);
            } else {
                return new Response('', 401);
            }
        } else {
            return new Response('', 410);
        }
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

    /**
     * Request 
     * 
     * @Route("/desires/{id}", name="delete_desire")
     * @Method("DELETE")
     * 
     * @return json
     */
    public function deleteDesireAction($id)
    {

        $user = $this->get('user');

        $request = $this->getRequest();
        $gameId = $request->get('gameId');
        $em = $this->getDoctrine()->getEntityManager();
        $desireRepo = $em->getRepository('DanMainBundle:Desire');
        $desire = $desireRepo->findOneById($id);

        $response = new Response();
        if ($desire) {
            if ($desire->getOwner()->getId() == $user->getId()) {
                $em->remove($desire);
                $em->flush();
                $response->setStatusCode(200);
            } else {
                $response->setStatusCode(401);
            }
        } else {
            $response->setStatusCode(410);
        }

        return $response;
    }

}
