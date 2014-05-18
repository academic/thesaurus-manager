<?php

use Artdarek\Neo4j\Facades\Neo4j;

class NodesController extends BaseController {

    public function getIndex() {
        return View::make('nodes/index');
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

        $thesarus = Neo4j::makeNode();
        $thesarus->setProperty('name', 'Arthur Dent')
                ->save();

        $thesarusId = $thesarus->getId();
        echo "<pre>";
        print_r($thesarusId);
        exit();
    }

}
