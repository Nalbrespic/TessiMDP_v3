<?php

namespace TMD\ColissimoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager('colissimo');
        $tr = $em->getRepository('TMDColissimoBundle:Trackings')->findAll();

        return $this->render('TMDColissimoBundle:Default:index.html.twig');
    }
}
