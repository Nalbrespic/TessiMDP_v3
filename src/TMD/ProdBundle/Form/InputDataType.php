<?php

namespace TMD\ProdBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InputDataType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date1', DateType::class, array(
                'widget'    => 'single_text',
                'input'     =>'datetime',
                'html5'     => false,
                'attr'      =>['class' => 'js-datepicker'],
                ))
            ->add('date2', DateType::class, array(
                'widget'    => 'single_text',
                'html5'     => false,
                'attr'      =>['class' => 'js-datepicker'],
            ))
            ->add('Valide',      SubmitType::class)
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TMD\ProdBundle\Entity\InputData'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tmd_prodbundle_inputdata';
    }


}
