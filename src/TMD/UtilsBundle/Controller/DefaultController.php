<?php

namespace TMD\UtilsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TMDUtilsBundle:Default:index.html.twig');
    }
}
