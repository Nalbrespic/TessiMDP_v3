<?php

namespace TMD\AppliBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NTrackingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('box',                    TextType::class, array(
                'label'         => 'Référence du colis:',
                'data'          =>""
            ))
            ->add('plagedebut',             TextType::class, array(
                'label'         => 'Premier N° de carte:',
                'data'          =>""
            ))
            ->add('plagefin',               TextType::class, array(
                'label'         => 'Dernier N° de carte:',
                'data'          =>""
            ))
            ->add('Valide',                 SubmitType::class)

        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TMD\AppliBundle\Entity\NTracking'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tmd_applibundle_ntracking';
    }


}
