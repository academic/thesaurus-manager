<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ThesaurusSuggestions extends Command {

    /**
     *
     * @var string
     */
    protected $name = 'suggestions:thesaurus';

    /**
     *
     * @var string
     */
    protected $description = 'Get suggestions from http://thesaurus.com';
    protected $url = 'http://thesaurus.com/browse/';

    /**
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * @return void
     */
    public function fire() {
        $value = $this->argument('word');
        echo "<pre>";
        print_r($value);
        exit();
    }

    /**
     *
     * @return array
     */
    protected function getArguments() {
        return array(
            array('word', InputArgument::REQUIRED, 'Word'),
        );
    }

    /**
     *
     * @return array
     */
    protected function getOptions() {
        return array();
    }

}
