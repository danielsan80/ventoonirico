<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Iabadabadu\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class CustomerEmailsType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email_ordini', 'text', array( 'label'=> 'Conferma d\'ordine:', 'required' => true))
            ->add('email_ordini2', 'text', array( 'label'=> '2° email Conferma d\'ordine:', 'required' => false))
            ->add('email_fatture', 'text', array( 'label'=> 'Email per Ricevimento fatture:', 'required' => false))
            ->add('email_fatture_is_pec', 'checkbox', array( 'label'=> 'E\' una PEC?', 'required' => false))
            ->add('email_comunicazioni', 'text', array( 'label'=> 'Comunicazioni Arbo:', 'required' => false))
            ->add('email_variazione_prezzi', 'text', array( 'label'=> 'Variazioni prezzi:', 'required' => false))
            ->add('email_pec', 'text', array( 'label'=> 'La tua PEC:', 'required' => false))
        ;
        
        $builder->addEventListener(FormEvents::POST_BIND, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            $user = $form->getData();
            $user->unsetMetadata('has_checked_his_profile');
//            $user->addGroup(
//                    $this->container->get('fos_user.group_manager')->findGroupByName('customer')
//                );
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'iabadabadu_user_profile_emails';
    }
}
