<?php

namespace Dan\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Regex;

class ColorType extends AbstractType
{
    public function getDefaultOptions(array $options)
    {
        $collectionConstraint = new Collection(array(
            'regexp' => new Regex(array('pattern' => '/^#[0-9a-f]{6}$/')),
        ));

        return array('validation_constraint' => $collectionConstraint);
    }
    
    public function getParent(array $options)
    {
        return 'text';
    }

    public function getName()
    {
        return 'dan_color';
    }
}