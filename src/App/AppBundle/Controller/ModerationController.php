<?php

namespace App\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Everyman\Neo4j\Client;
use Everyman\Neo4j\Index\NodeIndex;
use App\AppBundle\Entity\Node;

class ModerationController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render(
            'AppAppBundle:Moderation:index.html.twig'
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        return $this->render('AppAppBundle:Moderation:new.html.twig',array(
                'results' => $this->nodesByApproved(0)
            )
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function declinedAction()
    {
        return $this->render('AppAppBundle:Moderation:new.html.twig',array(
                'results' => $this->nodesByApproved(-1)
            )
        );
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Everyman\Neo4j\Exception
     * @throws \Exception
     */
    public function approveAction($id)
    {
        $client = new Client($this->container->getParameter('neo4j.host'));
        $nodeEntity = new Node($this->container->getParameter('neo4j.host'));
        $node = $client->getNode($id);
        $node->setProperty('approve', 1);
        $node->save();
        $nodeEntity->renewNodeIndex($nodeEntity->getIndex($client), $node);
        return $this->redirect('/admin/moderation/new');
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Everyman\Neo4j\Exception
     * @throws \Exception
     */
    public function declineAction($id)
    {
        $client = new Client($this->container->getParameter('neo4j.host'));
        $nodeEntity = new Node($this->container->getParameter('neo4j.host'));
        $node = $client->getNode($id);
        $node->setProperty('approve', -1);
        $node->save();
        $nodeEntity->renewNodeIndex($nodeEntity->getIndex($client), $node);
        return $this->redirect('/admin/moderation/new');
    }

    /**
     * @param int $approve
     * @return array
     */
    public function nodesByApproved($approve = 1)
    {
        $client = new Client($this->container->getParameter('neo4j.host'));
        $thesarusIndex = new NodeIndex($client, 'thesaurus');
        $matches = $thesarusIndex->query('approve:"' . $approve . '"');
        $results = array();
        foreach ($matches as $m) {
            $results[] = array('properties' => $m->getProperties(), 'id' => $m->getId());
        }
        return $results;
    }
}
