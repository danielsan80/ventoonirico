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
        return array();
    }
    
}
