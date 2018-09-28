<?php

namespace TMD\DpdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TMDDpdBundle:Default:index.html.twig');
    }
}
