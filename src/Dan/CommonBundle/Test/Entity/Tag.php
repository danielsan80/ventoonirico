<?php

namespace Dan\CommonBundle\Test\Entity;

class Tag implements \Dan\CommonBundle\Form\Tagit\TagInterface
{
    private $name;
    
    public function __construct($string) 
    {
        $this->name = $string;
    }
    
    public function getName()
    {
        return $this->name;
    }
}