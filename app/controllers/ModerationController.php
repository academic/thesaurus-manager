<?php

use Everyman\Neo4j\Client;
use Everyman\Neo4j\Index\NodeIndex;

class ModerationController extends BaseController {

    public function getIndex() {
        return View::make('moderation/index');
    }

    public function getNew() {
        return View::make('moderation/new', array('results' => $this->getNodesByApproved(0)));
    }

    public function getDeclined() {
        return View::make('moderation/new', array('results' => $this->getNodesByApproved(-1)));
    }

    public function getApprove($id) {
        $client = new Client(Config::get('database.connections.neo4j.default')['host']);
        $node = $client->getNode($id);
        $node->setProperty('approve', 1);
        $node->save();
        Node::renewNodeIndex(Node::getIndex($client), $node);
        return Redirect::to('/admin/moderation/new');
    }

    public function getDecline($id) {
        $client = new Client(Config::get('database.connections.neo4j.default')['host']);
        $node = $client->getNode($id);
        $node->setProperty('approve', -1);
        $node->save();
        Node::renewNodeIndex(Node::getIndex($client), $node);
        return Redirect::to('/admin/moderation/new');
    }

    private function getNodesByApproved($approve = 1) {
        $client = new Client(Config::get('database.connections.neo4j.default')['host']);
        $thesarusIndex = new NodeIndex($client, 'thesaurus');
        $matches = $thesarusIndex->query('approve:"' . $approve . '"');
        $results = array();
        foreach ($matches as $m) {
            $results[] = array('properties' => $m->getProperties(), 'id' => $m->getId());
        }
        return $results;
    }

}
