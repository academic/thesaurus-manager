<?php

namespace App\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ModerationController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AppAppBundle:Default:index.html.twig', array('name' => $name));
    }
}
