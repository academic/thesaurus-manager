<?php

use Illuminate\Console\Command;
use Artdarek\Neo4j\Facades\Neo4j as Neo4j;

class SampleData extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sampledata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert sample data to neo4j';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
        Node::createIndex();
        $progress = $this->getHelperSet()->get('progress');
        $progress->start($this->getOutput(), 100);
        $root = Node::addNode("biology", "en", TRUE);
        foreach (array("bacteria", "virus", "microbe", "pathogen", "anatomy") as $word) {
            $thesaurus = Node::addNode($word, "en", TRUE);
            Node::addRelation($thesaurus, $root, 100);
            Node::addRelation($root, $thesaurus, 100);
            $progress->advance(4);
        }

        $root2 = Node::addNode("virus", "en", TRUE);
        foreach (array("hiv", "ebola", "hepatitis b", "smallpox", "b19") as $word) {
            $thesaurus = Node::addNode($word, "en", TRUE);
            Node::addRelation($thesaurus, $root2, 100);
            Node::addRelation($root2, $thesaurus, 100);
            $progress->advance(4);
        }

        $root3 = Node::addNode("bacteria", "en", TRUE);
        foreach (array("clostridium botulinum", "salmonella", "tetanus", "vibrio cholera", "chlamydia") as $word) {
            $thesaurus = Node::addNode($word, "en", TRUE);
            Node::addRelation($thesaurus, $root3, 100);
            Node::addRelation($root3, $thesaurus, 100);
            $progress->advance(4);
        }

        $root4 = Node::addNode("web development", "en", TRUE);
        foreach (array("php", "nodejs", "ruby", "python", "javascript") as $word) {
            $thesaurus = Node::addNode($word, "en", TRUE);
            Node::addRelation($thesaurus, $root4, 100);
            Node::addRelation($root4, $thesaurus, 100);
            $progress->advance(4);
        }

        $root5 = Node::addNode("archaeology", "en", TRUE);
        foreach (array("hittite", "paleontology", "prehistory", "excavation", "paleology") as $word) {
            $thesaurus = Node::addNode($word, "en", TRUE);
            Node::addRelation($thesaurus, $root5, 80);
            Node::addRelation($root5, $thesaurus, 80);
            $progress->advance(4);
        }
        $this->comment("\nAdded sample nodes and relations");
        $this->comment("Try searching 'bacteria' from '/nodes/search' ");
        $progress->finish();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return array();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
        return array();
    }

}
