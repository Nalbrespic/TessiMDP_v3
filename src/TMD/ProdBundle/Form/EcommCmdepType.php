<?php

namespace TMD\ProdBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EcommCmdepType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('idclient')
//            ->add('idfile')
//            ->add('numcmde')
//            ->add('codearticle')
//            ->add('quantite')
//            ->add('type')
//            ->add('numseq')
//            ->add('perso1')
//            ->add('perso2')
//            ->add('libelle')
//            ->add('poids')
//            ->add('epaisseur')
//            ->add('nomimg')
//            ->add('flagart')
//            ->add('record')
//            ->add('numOrder')
//            ->add('nbPages')
            ->add('numRef', TextType::class)
//            ->add('numTrack')
//            ->add('numbl')
            ->add('')
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TMD\ProdBundle\Entity\EcommCmdep'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tmd_prodbundle_ecommcmdep';
    }


}
