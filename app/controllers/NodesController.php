<?php

use Artdarek\Neo4j\Facades\Neo4j;

class NodesController extends BaseController {

    public function getIndex() {
        return View::make('nodes/index');
    }

    public function getAdd() {
        return View::make('nodes/add');
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
