<?php

namespace TMD\ColisPriveBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager('colisprive');
        $tr = $em->getRepository('TMDColisPriveBundle:Trackings')->findAll();
        return $this->render('TMDColisPriveBundle:Default:index.html.twig');
    }
}
