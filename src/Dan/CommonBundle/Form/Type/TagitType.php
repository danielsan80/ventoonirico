<?php

namespace Dan\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Regex;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

use Dan\CommonBundle\Form\DataTransformer\TagCollectionToArrayTransformer;
use Doctrine\ORM\EntityManager;

use Dan\CommonBundle\Form\Tagit\TagRepositoryInterface;

class TagitType extends AbstractType
{
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        if (!isset($options['repository'])) {
            throw new \Exception('Set the tag "repository" option for the Tagit field');
        }
        
        if (!($options['repository'] instanceof TagRepositoryInterface)) {
            throw new \Exception('Set the tag "repository" option must implement Dan\CommonBundle\Form\Tagit\TagRepositoryInterface');
        }
        
        $builder
            ->setAttribute('object_id', $options['object_id'])
        ;
        $transformer = new TagCollectionToArrayTransformer($options['repository']);
        $builder->appendClientTransformer($transformer);
    }

    
    public function buildView(FormView $view, FormInterface $form)
    {
        $view
            ->set('object_id', $form->getAttribute('object_id'))
        ;
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'repository' => null,
            'object_id' => null,
        );
    }
    
    public function getParent(array $options)
    {
        return 'text';
    }

    public function getName()
    {
        return 'dan_tagit';
    }
}