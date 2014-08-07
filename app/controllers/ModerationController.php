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
    }

    public function getDecline($id) {
        /**
         *  @todo add a filter
         */
        if (!Sentry::check()) {
            return Redirect::to('/account/login');
        }
    }

    private function getNodesByApproved($approved = 1) {
        $client = new Everyman\Neo4j\Client(Config::get('database.connections.neo4j.default')['host']);
        $thesarusIndex = new Everyman\Neo4j\Index\NodeIndex($client, 'thesaurus');
        $matches = $thesarusIndex->query('approved:' . $approved);
        $results = array();
        foreach ($matches as $m) {
            $results[] = array('properties' => $m->getProperties(), 'id' => $m->getId());
        }
        return $results;
    }

}
