<?php

use Everyman\Neo4j\Traversal;
use Everyman\Neo4j\Relationship;

class ModerationController extends BaseController {

    public function getIndex() {
        return View::make('moderation/index');
    }

    public function getNew() {
        return View::make('moderation/new', array('results' => $this->getNodesByApproved(0)));
    }

    public function getDeclined() {
        return View::make('moderation/new', array('results' => $this->getNodesByApproved(0)));
    }

    public function getApprove($id) {
        /**
         *  @todo add a filter
         */
        if (!Sentry::check()) {
            return Redirect::to('/account/login');
        }
        $user = Sentry::getUser();
        if (!$user->hasAccess('admin')) {
            App::abort(401, 'Not authenticated');
        }

        $client = new Everyman\Neo4j\Client(Config::get('database.connections.neo4j.default')['host']);
        $node = $client->getNode($id);
        $node->setProperty('approve', 1);
        $node->save();
        Node::renewNodeIndex(Node::getIndex($client), $node);
        return Redirect::to('/moderation/new');
    }

    public function getDecline($id) {
        /**
         *  @todo add a filter
         */
        if (!Sentry::check()) {
            return Redirect::to('/account/login');
        }
        $user = Sentry::getUser();
        if (!$user->hasAccess('admin')) {
            App::abort(401, 'Not authenticated');
        }

        $client = new Everyman\Neo4j\Client(Config::get('database.connections.neo4j.default')['host']);
        $node = $client->getNode($id);
        $node->setProperty('approve', -1);
        $node->save();
        Node::renewNodeIndex(Node::getIndex($client), $node);
        return Redirect::to('/moderation/new');
    }

    private function getNodesByApproved($approve = 1) {
        $client = new Everyman\Neo4j\Client(Config::get('database.connections.neo4j.default')['host']);
        $thesarusIndex = new Everyman\Neo4j\Index\NodeIndex($client, 'thesaurus');
        $matches = $thesarusIndex->query('approve:' . $approve);
        $results = array();
        foreach ($matches as $m) {
            $results[] = array('properties' => $m->getProperties(), 'id' => $m->getId());
        }
        return $results;
    }

}
