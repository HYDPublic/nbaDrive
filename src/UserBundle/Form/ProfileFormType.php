<?php
// src/AppBundle/Form/RegistrationType.php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('locale', TextType::class)
            ->add('country',    EntityType::class, array(
                    'class' => 'UserBundle:Country',
                    'choice_label'  => 'name',
                    'multiple'  => false,
                ))
            ->add('favoriteTeam', EntityType::class, array(
                    'class' => 'PlayoffBundle:Team',
                    'choice_label'  => 'name', 
                    'multiple'  => false,
                ))
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';

    }

    public function getBlockPrefix()
    {
        return 'app_user_profile_edit';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
