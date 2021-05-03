<?php
/**
 * Created by PhpStorm.
 * User: jjoyeux
 * Date: 26/10/2018
 * Time: 12:22
 */

namespace TMD\AppliBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TMD\AppliBundle\Entity\EcommCompte;
use TMD\AppliBundle\Entity\EcommCompteTransport;
use TMD\AppliBundle\Entity\EcommTransport;

class EcommAppliConfigType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('idtransportCompte', EcommCompteTransportType::class);
//            ->add('idtransportCompte', EntityType::class, array(
//                'class' => EcommCompteTransport::class,
//                'choice_label' => function ($category) {
//                    return $category->getIdtransport()->getLibelletransport();
//                }
//            ));
//            ->add('idCompteTransport', EntityType::class, array(
//                'class' => EcommCompteTransport::class,
//                'choice_label' => function ($category) {
//                    return $category->getIdcomptetransport();
//                },
//            ))
            ->add('commentaire' , TextType::class)
//            ->add('idTransport', EntityType::class, array(
//            'class' => EcommTransport::class,
//            'query_builder' => function (EntityRepository $er) {
//                return $er->createQueryBuilder('u')
//                    ->groupBy('u.idtransporteur');
//            },
//            'choice_label' => function ($category) {
//                return $category->getIdtransporteur()->getLibelletransport();
//            },
//            'placeholder'  =>''
//            ))


        ;




//        $formModifier = function (FormInterface $form, EcommCompte $compte = null) {
//            $transport = null === $compte ? array() : $compte->getCompteTransport();
//
//            $form->add('idCompte', EntityType::class, array(
//                'class' => EcommTransport::class,
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('tr')
//                        ->innerJoin('tr.CompteTransports', 'trCp')
//                        ->where('trCp  = :compt')
//                        ->setParameter('compt', 1)
//                        ;
//                },
//                'choice_label' => function ($category) {
//                return $category->getCompteTransport();
//            },
////                'choices_as_values' => true,
//            ));
//        };
//
//
//        $builder->addEventListener(
//            FormEvents::PRE_SET_DATA,
//            function (FormEvent $event) use ($formModifier) {
//                // this would be your entity, i.e. SportMeetup
//
//                $data = null;
//                if ($data != null){
//                    $data = $event->getData();
//                }
//                $formModifier($event->getForm(), $data);
//            }
//        );
//
//        $builder->get('idcompte')->addEventListener(
//            FormEvents::POST_SUBMIT,
//            function (FormEvent $event) use ($formModifier) {
//                // It's important here to fetch $event->getForm()->getData(), as
//                // $event->getData() will get you the client data (that is, the ID)
//                $sport = $event->getForm()->getData();
//                // since we've added the listener to the child, we'll have to pass on
//                // the parent to the callback functions!
//                $formModifier($event->getForm()->getParent(), $sport);
//            }
//        );



    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TMD\AppliBundle\Entity\EcommAppliConfig'
        ));


    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tmd_applibundle_ecommAppliConfig';
    }
}