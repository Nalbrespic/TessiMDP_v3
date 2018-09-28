<?php

namespace TMD\IcomedBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class IcomedController extends Controller
{
    public function indexAction()
    {
//        return $this->render('@TMDIcomed/Icomed/index.html.twig');

        return new RedirectResponse('http://tmdsws801/portail/icomed/index.php');
    }
}
