<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Hasantayyar\Synonyms\Synonyms;
class ThesaurusSuggestions extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'suggestions:thesaurus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get synonyms from thesaurus.com.';

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
        $synonyms = new Synonyms($word);
        $synonyms->printThesaurus();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return array(
            array('word', InputArgument::REQUIRED,
                'A word to get similars.'),
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
