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
        if ($this->getRequest()->getHost()=='ventoonirico') {
            return $this->redirect('http://ventoonirico.local.com'.$this->generateUrl('home'));
        }
        if (strrpos($this->getRequest()->getRequestUri(),'/')!==0) {
            return $this->redirect(substr($this->generateUrl('home'),0,-1));
        }
        return array();
    }
}
