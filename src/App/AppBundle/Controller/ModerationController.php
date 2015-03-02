<?php

namespace App\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Everyman\Neo4j\Client;
use Everyman\Neo4j\Index\NodeIndex;
use App\AppBundle\Entity\Node;

class ModerationController extends Controller
{
    protected $client;
    protected $node;

    public function __construct(){

        $this->client = new Client();
        $this->node = new Node();
    }
    public function indexAction()
    {
        return $this->render(
            'AppAppBundle:Moderation:index.html.twig'
        );
    }

    public function newAction()
    {
        return $this->render('AppAppBundle:Moderation:new.html.twig',array(
                'results' => $this->nodesByApproved(0)
            )
        );
    }

    public function declinedAction()
    {
        return $this->render('AppAppBundle:Moderation:new.html.twig',array(
                'results' => $this->nodesByApproved(-1)
            )
        );
    }

    public function approveAction($id)
    {
        $nodeEntity = new Node();
        $node = $this->client->getNode($id);
        $node->setProperty('approve', 1);
        $node->save();
        $nodeEntity->renewNodeIndex($nodeEntity->getIndex($this->client), $node);
        return $this->redirect('/admin/moderation/new');
    }

    public function declineAction($id)
    {
        $nodeEntity = new Node();
        $node = $this->client->getNode($id);
        $node->setProperty('approve', -1);
        $node->save();
        $nodeEntity->renewNodeIndex($nodeEntity->getIndex($this->client), $node);
        return $this->redirect('/admin/moderation/new');
    }

    public function nodesByApproved($approve = 1)
    {
        $thesarusIndex = new NodeIndex($this->client, 'thesaurus');
        $matches = $thesarusIndex->query('approve:"' . $approve . '"');
        $results = array();
        foreach ($matches as $m) {
            $results[] = array('properties' => $m->getProperties(), 'id' => $m->getId());
        }
        return $results;
    }
}
