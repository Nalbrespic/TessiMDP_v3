<?php

namespace TMD\ProdBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EcommLignesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('idclient')
//            ->add('idfile')
            ->add('numbl')
            ->add('poids', TextType::class, array(
                'label' => 'Poids (gr)'
            ))
            ->add('numligne', EntityType::class, [
                'class'  => 'TMD\ProdBundle\Entity\EcommTracking',
            ])
//            ->add('montant')
//            ->add('epaisseur')
//            ->add('numOrder')
            ->add('numligne', EcommTrackingType::class)
            ->add('Valider',      SubmitType::class)

        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TMD\ProdBundle\Entity\EcommLignes'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tmd_prodbundle_ecommlignes';
    }


}
