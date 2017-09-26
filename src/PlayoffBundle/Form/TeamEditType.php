<?php
namespace PlayoffBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TeamEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('aid',            TextType::class)
            ->add('name',           TextType::class)
            ->add('shortname',      TextType::class)
            ->add('conference',     TextType::class)
            ->add('rank',           IntegerType::class)
            ->add('logo',           FileType::class, array(
                        'data_class'=> null,
                        'required'  => false,
                    )
                )
            ->add('save',    	    SubmitType::class)
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PlayoffBundle\Entity\Team',
        ));
    }
}
