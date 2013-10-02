<?php
namespace Dan\CommonBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use Dan\CommonBundle\Form\Tagit\TagRepositoryInterface;

class TagCollectionToArrayTransformer implements DataTransformerInterface
{

    private $repository;
    
    public function __construct(TagRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function transform($tags)
    {
        $array = array();
        if (!$tags) {
            return $array;
        }
        foreach($tags as $tag) {
            $array[] = $tag->getName();
        }
        
        return $array;
    }
    
    public function reverseTransform($array)
    {
        $tags = new ArrayCollection();
        if (!$array) {
            return $tags;
        }
        foreach($array as $string) {
            $tags->add($this->repository->createTag($string));
        }

        return $tags;
    }
}