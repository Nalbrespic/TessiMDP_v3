<?php

namespace TMD\ConfigBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;

use http\Env\Response;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TMD\ConfigBundle\Entity\News;
use TMD\ConfigBundle\Form\NewsType;
use TMD\ProdBundle\Entity\Client;
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








}
