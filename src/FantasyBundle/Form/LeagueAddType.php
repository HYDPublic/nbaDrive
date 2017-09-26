<?php
namespace FantasyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LeagueAddType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name',  TextType::class)
            ->add('private',    CheckboxType::class, array(
                    'label'     => 'Privé',
                    'required'  => false,
                ))
            ->add('stats_points',   TextType::class, array('mapped'=>false))
            ->add('stats_rebonds',  TextType::class, array('mapped'=>false))
            ->add('stats_assists',  TextType::class, array('mapped'=>false))
            ->add('stats_double',   TextType::class, array('mapped'=>false))
            ->add('stats_triple',   TextType::class, array('mapped'=>false))

            ->add('password', TextType::class, array('required'=>false))
            ->add('save',           SubmitType::class)
        ;

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FantasyBundle\Entity\League',
        ));
    }
}
