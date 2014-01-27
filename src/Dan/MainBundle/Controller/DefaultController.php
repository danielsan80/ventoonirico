<?php

namespace Dan\MainBundle\Controller;

use Dan\CommonBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("") 
 */
class DefaultController extends Controller
{

    /**
     * Home page
     * 
     * @Route("", name="home")
     * @Template
     */
    public function indexAction()
    {
        $this->get('session')->set('slack', true);
        if ($response = $this->checkUrl('home')) {
            return $response;
        }
        return array();
    }

    /**
     * Spec Runner
     * 
     * @Route("test", name="test")
     * @Template
     */
    public function testAction()
    {
        if ($response = $this->checkUrl('test')) {
            return $response;
        }
        return array();
    }

    private function checkUrl($route, $args = array())
    {
        if ($this->getRequest()->getHost() == 'ventoonirico') {
            return $this->redirect('http://ventoonirico.local.com' . $this->generateUrl());
        }
        $uri = explode('#', $this->getRequest()->getRequestUri());
        $uri = explode('?', $uri[0]);
        $uri = $uri[0];        
        if (substr($uri, -1, 0) == '/') {
            return $this->redirect(substr($this->generateUrl($route, $args), 0, -1));
        }
        return null;
    }

}
