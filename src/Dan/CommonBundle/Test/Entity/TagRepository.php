<?php

namespace Dan\CommonBundle\Test\Entity;

class TagRepository implements \Dan\CommonBundle\Form\Tagit\TagRepositoryInterface
{
    public function createTag($string)
    {
        return new Tag($string);
    }
}