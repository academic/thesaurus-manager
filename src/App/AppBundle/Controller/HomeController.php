<?php

namespace App\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function showIndexAction()
    {
        if($this->container->get('security.context')->isGranted('ROLE_USER')){
            return $this->render('AppAppBundle:Home:dashboard.html.twig');
        }else{
            return $this->render('AppAppBundle:Home:anonym.html.twig');
        }
    }
}
