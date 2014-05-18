<?php

use Artdarek\Neo4j\Facades\Neo4j as Neo4j;

class NodesController extends BaseController {

    public function getIndex() {
        return View::make('nodes/index');
    }

    public function postSearch() {
        $word = Input::get('thesaurus');
        $client = new Everyman\Neo4j\Client();
        $thesarusIndex = new Everyman\Neo4j\Index\NodeIndex($client, 'thesaurus');
        $matches = $thesarusIndex->query('word:*' . $word . '*');
        $results = array();
        foreach ($matches as $m) {
            $results[] = array('properties' => $m->getProperties(), 'id' => $m->getId());
        }
        return View::make('nodes/search', array('results' => $results));
    }

    public function getSearch() {

        return View::make('nodes/search');
    }

    public function getAdd() {
        return View::make('nodes/add');
    }

    public function getGraphEditor($id = NULL) {
        return View::make('nodes/graph-editor');
    }

    public function postAdd() {
        $word = Input::get('thesaurus');
        $client = new Everyman\Neo4j\Client();
        $thesarusIndex = new Everyman\Neo4j\Index\NodeIndex($client, 'thesaurus');

        $thesarus = Neo4j::makeNode();
        $thesarus->setProperty('word', $word)
                ->save();

        $thesarusIndex->add($thesarus, 'word', $thesarus->getProperty('word'));

        $thesarusId = $thesarus->getId();
        return Redirect::to('nodes/add');
    }

}
