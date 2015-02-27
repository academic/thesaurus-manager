<?php

namespace App\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Everyman\Neo4j\Traversal;
use Everyman\Neo4j\Relationship;

class NodesController extends Controller
{
    public function indexAction()
    {
        return $this->render(
            'AppAppBundle:Nodes:index.html.twig'
        );
    }

    public function getSearchAction()
    {

    }

    public function postSearchAction()
    {

    }

    public function getAddAction()
    {

    }

    public function postAddAction()
    {

    }

    public function getAddNodeAction($relatedId)
    {

    }

    public function postAddSynonymAction()
    {

    }

    public function getGraphAction()
    {

    }
}
