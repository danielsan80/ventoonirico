<?php

namespace Dan\NoleaksBundle\Tests\Form\DataTransformer;

use Dan\CommonBundle\Test\WebTestCase;
use Dan\CommonBundle\Form\DataTransformer\TagCollectionToArrayTransformer;

/**
 * DateHelper Tests 
 */
class TagCollectionToArrayTransformerTest extends WebTestCase
{

    public function test_transform()
    {
        $tagRepository = new \Dan\CommonBundle\Test\Entity\TagRepository();
        
        $transformer = new TagCollectionToArrayTransformer($tagRepository);
        
        $array = array('tag1', 'tag2');
        
        $tags = $transformer->reverseTransform($array);
        
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $tags);
        $this->assertCount(2, $tags);
        $this->assertInstanceOf('Dan\CommonBundle\Form\Tagit\TagInterface', $tags[0]);
        $this->assertEquals('tag1', $tags[0]->getName());
        $this->assertEquals('tag2', $tags[1]->getName());
        
        
        $array = $transformer->transform($tags);
        
        $this->assertCount(2, $array);
        $this->assertEquals('tag1', $array[0]);
        $this->assertEquals('tag2', $array[1]);
    }
}
