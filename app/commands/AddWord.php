<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class AddWord extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'word:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add new word';

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
        $word = $this->argument('word');
        Node::addNode($word);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return array(
            array('word', InputArgument::REQUIRED,
                'The word to be added'),
        );
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
