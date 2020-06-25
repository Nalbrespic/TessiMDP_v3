<?php

namespace TMD\ConfigBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;

use http\Env\Response;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TMD\ConfigBundle\Entity\News;
use TMD\ConfigBundle\Form\NewsType;
use TMD\ProdBundle\Entity\EcommStatut;
use TMD\ProdBundle\Entity\EcommTracking;
use TMD\ProdBundle\Form\EcommTrackingType;
use TMD\UserBundle\Entity\User;
use TMD\UserBundle\Form\UserType;

class GestionController extends Controller

{


    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('TMDUserBundle:User')->findAll();

        $user = new User();


        $form = $this->get('form.factory')->create(UserType::class, $user);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
//            $plainPassword = $form->getData()->getPassword();
//            $password = $this->get('security.encoder_factory')->getEncoder($user)->encodePassword($plainPassword, $user->getSalt());
//            $user->setPassword($password);
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
//            $user->setSalt($user->getSalt());
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();


            return $this->redirectToRoute('tmd_config_homepage');
        }


        return $this->render('TMDConfigBundle:gestion:index.html.twig', array(
            'users' => $users,
            'form' => $form->createView()
        ));
    }


    public function addnewAction(Request $request)
    {
        $new = new News();
        $form = $this->get('form.factory')->create(NewsType::class, $new);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $news = $em->getRepository('TMDConfigBundle:News')->findAll();
            foreach ($news as $newi) {
                if ($newi->getRole() == $form->getData()->getRole()) {
                    $newi->setPublished(false);
                }
            }

            $em = $this->getDoctrine()->getManager();

            $em->persist($new);
            $em->flush();


            return $this->redirectToRoute('tmd_core_homepage');
        }
        return $this->render('TMDConfigBundle:gestion:addNew.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function gestioncmdAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $clients = $em->getRepository('TMDProdBundle:Client')->findBy(array(),
            array('nomclient' => 'ASC')
        );

        return $this->render('TMDConfigBundle:gestion:gestionCmde.html.twig', array(
            'clients' => $clients
        ));
    }

    public function gestioncmdClientAction(Request $request)
    {

        $idClient = $request->get('idClient');
        dump($idClient);
        $em = $this->getDoctrine()->getManager();
        $clients = $em->getRepository('TMDProdBundle:Client')->findBy(array(),
            array('nomclient' => 'ASC')
        );
        $client = $em->getRepository('TMDProdBundle:Client')->findBy(
            array('idclient' => $idClient
            ));
        $listtrackings = $em->getRepository('TMDProdBundle:EcommTracking')->trackingByIdclient($idClient);
        $trackings = $this->get('knp_paginator')->paginate($listtrackings,
            $request->query->get('page', 1), 25);


        return $this->render('TMDConfigBundle:gestion:gestionCmde.html.twig', array(
            'clients' => $clients,
            'client' => $client[0],
            'idClient' => $idClient,
            'trackings' => $trackings
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editTrackingAction(Request $request)
    {
        $numligne = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $tracking = $em->getRepository('TMDProdBundle:EcommTracking')->trackingByNumligne($numligne);


       $form = $this->get('form.factory')->createBuilder(EcommTrackingType::class, $tracking)

            ->getForm();

        dump($request);
        dump($tracking);
        dump($form);
        if ($request->isMethod('POST')){
            $form->handleRequest($request);

            if ($form->isValid()){
                $em = $this->getDoctrine()->getManager();

                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'modif effectuée');
//                return $this->redirectToRoute('tmd_config_gestioncmdClient', array('idClient'=>$tracking['0']['idclient']));
            }

        }

//        $form = $this->createFormBuilder($tracking)
//            ->setMethod('POST')
//            ->add('idStatut', EntityType::class, [
//
//                'class' => EcommStatut::class,
//                'choice_label' => 'statut'
//            ])
//            ->add('refClient')
//            ->add('Modifier', SubmitType::class)
//            ->getForm();
//
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            dump($form);
//            dump($form->getData()['idStatut']->getIdStatut());
//            dump($tracking);
////            $tracking['0']['idStatut']->set($form->getData()['idStatut']->getIdStatut());
//            $em->flush();
////            return $this->redirect($this->generateUrl('tmd_config_gestioncmdClient', [
////                'idClient' => $tracking['0']['idclient']
////            ]));
//        }


        return $this->render('TMDConfigBundle:gestion:editTracking.html.twig', array(
            'tracking' => $tracking['0'],
            'form' => $form->createView()
        ));
    }


}
