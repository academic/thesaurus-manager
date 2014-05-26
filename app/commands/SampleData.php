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
        $progress->start($this->getOutput(), 50);
        $root = Node::addNode("Biology");
        foreach (array("Bacteria", "Virus", "Microbe", "Pathogen", "Anatomy") as $word) {
            $thesaurus = Node::addNode($word);
            Node::addRelation($thesaurus, $root, 100);
            Node::addRelation($root, $thesaurus, 100);

            $progress->advance(10);
        }
        $this->comment("\nAdded sample nodes and relations");
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
