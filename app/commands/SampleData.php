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
        $data = array("City", "Math", "Money", "Capital", "Bill", "Town", "Bacteria", "Biology", "Virus");
        $client = new Everyman\Neo4j\Client();
        foreach ($data as $word) {
            $thesarus = Neo4j::makeNode();
            $thesarus->setProperty('word', $word)
                    ->save();

            $thesarusIndex = new Everyman\Neo4j\Index\NodeIndex($client, 'thesaurus');
            $thesarusIndex->add($thesarus, 'word', $thesarus->getProperty('word'));

            $thesarusId = $thesarus->getId();
            echo $word . " added with " . $thesarusId . "\n";
        }
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
