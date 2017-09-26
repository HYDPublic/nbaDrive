<?php
namespace PlayoffBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GameAddType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('aid',            TextType::class)
            ->add('date',        TextType::class)
            ->add('teamExt',    EntityType::class, array(
                    'class' => 'PlayoffBundle:Team',
                    'choice_label'  => 'name',
                    'multiple'  => false,
                ))
            ->add('teamDom',    EntityType::class, array(
                    'class' => 'PlayoffBundle:Team',
                    'choice_label'  => 'name',
                    'multiple'  => false,
                ))
            ->add('round', IntegerType::class)
            ->add('save',    	    SubmitType::class)
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PlayoffBundle\Entity\Game',
        ));
    }
}
