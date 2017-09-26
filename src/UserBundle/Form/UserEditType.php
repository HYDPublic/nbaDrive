<?php
namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $this->allRoles = $options['roles']; 

        $builder
            ->add('username',	TextType::class)
            ->add('email',   	TextType::class)
            ->add('country',    EntityType::class, array(
                    'class' => 'UserBundle:Country',
                    'choice_label'  => 'name',
                    'multiple'  => false,
                ))
            ->add('locale',     TextType::class)
            ->add('enabled',    CheckboxType::class, array(
                    'label'     => 'Enable',
                    'required'  => false,
                ))
            ->add('roles', 	Choicetype::class, array(
            		'choices'	=> $this->allRoles,
                    'expanded'  => true,
                    'multiple'  => true,
                    'mapped'    => false,
            	))
            ->add('save',    	SubmitType::class)
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\User',
            'roles'      => null
        ));
    }
}
