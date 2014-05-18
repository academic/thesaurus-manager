<?php

use Artdarek\Neo4j\Facades\Neo4j;

class SuggestionsController extends BaseController {

    public function getIndex() {
        return View::make('suggestions/index');
    }

    public function getThesaurus() {
        return View::make('suggestions/thesaurus');
    }

    public function getGoogle() {
        return View::make('suggestions/thesaurus');
    }

}
