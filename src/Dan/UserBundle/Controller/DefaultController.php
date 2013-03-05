<?php

namespace Dan\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DanUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
