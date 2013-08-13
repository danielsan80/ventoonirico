<?php

namespace Dan\MainBundle\Controller;

use Dan\CommonBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Yaml\Yaml;

/**
 * @Route("/bb") 
 */
class BBController extends Controller
{
    /**
     * Home page
     * 
     * @Route("", name="bb")
     * @Template
     * @return html
     */
    public function indexAction()
    {
        return array();
    }
}
