<?php

namespace TMD\ProdBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TMD\ProdBundle\Repository\ClientRepository;

class EcommTrackingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('refclient')
//            ->add('numCmdeClient')
//            ->add('destinataire')
//            ->add('destRue')
//            ->add('destAd2')
//            ->add('destAd3')
//            ->add('destAd4')
//            ->add('destAd5')
//            ->add('destAd6')
//            ->add('destCp')
//            ->add('destVille')
//            ->add('destPays')
//            ->add('destTel')
//            ->add('destMail')
//            ->add('expCompte')
//            ->add('expRef')
//            ->add('codeAgenceTransp')
//            ->add('instrLivrais1')
//            ->add('instrLivrais2')
//            ->add('nbEtiquettes')
//            ->add('typeTransport')
//            ->add('tabNumblPoids')
//            ->add('poids')
//            ->add('expTel')
//            ->add('expMail')
//            ->add('relaisCode')
//            ->add('relaisPournom')
//            ->add('relaisPourprenom')
//            ->add('relaisNom')
//            ->add('relaisAd1')
//            ->add('relaisAd2')
//            ->add('relaisCp')
//            ->add('relaisVille')
//            ->add('dateInsert')
//            ->add('transNotification')
//            ->add('expId')
//            ->add('expRef1')
//            ->add('expRef2')
//            ->add('montant')
//            ->add('codeDouanier')
//            ->add('typeProduction')
//            ->add('flagEtikt')
//            ->add('flagExp')
//            ->add('dateDepot')
//            ->add('flagXport')
//            ->add('type')
//            ->add('crj')
//            ->add('idfile')
            ->add('idclient',   EntityType::class, array(
                'class'        => 'TMD\ProdBundle\Entity\Client',
                'choice_label' => 'nomclient',
                'multiple'     => false,
                'query_builder' => function(ClientRepository $repository) {
                    return $repository->myFindClientAsc();
                }
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TMD\ProdBundle\Entity\EcommTracking'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tmd_prodbundle_ecommtracking';
    }


}
