<?php

namespace TMD\ProdBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
//            ->add('expRef', TextType::class)
            ->add('destinataire', TextType::class)
            ->add('destRue', TextType::class)
            ->add('destAd2', TextType::class, array(
                'required' => false,
                'empty_data' => " ",
                'label' => 'Adresse2'
            ))
            ->add('destAd3', TextType::class, array(
                'required' => false,
                'empty_data' => " ",
                'label' => 'Adresse3'
            ))
            ->add('destAd4', TextType::class, array(
                'required' => false,
                'empty_data' => " ",
                'label' => 'Adresse4'
            ))
            ->add('destAd5', TextType::class, array(
                'required' => false,
                'empty_data' => " ",
                'label' => 'Adresse5'
            ))
            ->add('destAd6', TextType::class, array(
                'required' => false,
                'empty_data' => " ",
                'label' => 'Adresse6'
                        ))
            ->add('destCp', TextType::class, array(
                'label' => 'CP'
            ))
            ->add('destVille', TextType::class, array(
                'label' => 'Ville'
            ))
            ->add('destPays', TextType::class, array(
                'label' => 'Pays'
            ))
            ->add('destTel', TextType::class, array(
                'required' => false,
                'empty_data' => " ",
                'label' => 'Téléphone'
            ))
            ->add('destMail', TextType::class, array(
                'required' => false,
                'empty_data' => " ",
                'label' => 'Mail'
            ));

//            ->add('expCompte')
//            ->add('expRef')
//            ->add('codeAgenceTransp')
//            ->add('instrLivrais1')
//            ->add('instrLivrais2')
//            ->add('nbEtiquettes')
//            ->add('typeTransport')
//            ->add('tabNumblPoids')
//            ->add('poids', EntityType::class, array(
//                'class'     => 'TMD\ProdBundle\Entity\EcommLignes'
//            ))
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
//            ->add('idclient',   EntityType::class, array(
//                'class'        => 'TMD\ProdBundle\Entity\Client',
//                'choice_label' => 'nomclient',
//                'multiple'     => false,
//                'query_builder' => function(ClientRepository $repository) {
//                    return $repository->myFindClientAsc();
//                }
//            ));

    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => EcommTracking::class,
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
