<?php
namespace PlayoffBundle\Form;

use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GameEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('score',  TextType::class, array(
                    'required' => false,
                ))
        ;

        $builder->get('date')
            ->addModelTransformer(new CallbackTransformer(
                    function($dateAsObject){
                        return $dateAsObject->format('Y-m-d H:i');
                    },
                    function($dateAsString){
                        return new \Datetime($dateAsString);
                    }
                ))
        ;
    }

    public function getParent()
    {
        return GameAddType::class;
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
