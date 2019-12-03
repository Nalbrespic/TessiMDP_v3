<?php

namespace TMD\MinosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TMDMinosBundle:Default:index.html.twig');
    }
}
