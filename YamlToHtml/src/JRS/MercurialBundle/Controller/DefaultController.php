<?php

namespace JRS\MercurialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('JRSMercurialBundle:Default:index.html.twig', array());
    }

    public function loginAction()
    {
        return $this->render('JRSMercurialBundle:Security:login.html.twig', array());
    }
}
