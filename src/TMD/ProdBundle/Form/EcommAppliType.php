<?php

namespace TMD\ProdBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TMD\AppliBundle\Form\EcommAppliConfigType;
use TMD\ProdBundle\Repository\ClientRepository;

class EcommAppliType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('appliname')
//            ->add('applinameref')
//            ->add('cmde')
//            ->add('dateappli')
//            ->add('nbrecords')
            ->add('dossierimg')
//            ->add('gpmnttrans')
            ->add('appliImage', FileType::class, array(
                'required' => false,
                'data_class' => null
            ) )
            ->add('codeappli', TextType::class, array(
                'required' => true
            ))
            ->add('trtappli')
            ->add('mailing')
            ->add('idclient', EntityType::class, array(
                'class'        => 'TMD\ProdBundle\Entity\Client',
                'choice_label' => 'nomclient',
                'multiple'     => false,
                'query_builder' => function(ClientRepository $repository) {
                    return $repository->createQueryBuilder('u')
                        ->orderBy('u.nomclient', 'ASC');
                },
            ))
            ->add('configs', CollectionType::class, array(
                'entry_type' => EcommAppliConfigType::class,
//                'entry_options' => array(
//                    'attr' => array('class' => 'TMD\AppliBundle\Entity\EcommAppliConfig')
//                ),
                'allow_add'     => true,

            ))


            ->add('idtypeprod', EntityType::class, array(
                'class'        => 'TMD\ProdBundle\Entity\EcommTypeProduction',
                'choice_label' => 'libelle',
                'multiple'     => false,
            ))
            ->add('Valider',      SubmitType::class)
        ;






    }








    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TMD\ProdBundle\Entity\EcommAppli'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tmd_prodbundle_ecommappli';
    }


}
