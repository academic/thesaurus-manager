<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GoogleSuggestions extends Command {

    /**
     *
     * @var string
     */
    protected $name = 'suggestions:google';

    /**
     *
     * @var string
     */
    protected $description = 'Command description.';

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
        //
    }

    /**
     *
     * @return array
     */
    protected function getArguments() {
        return array(
            array('example', InputArgument::REQUIRED, 'An example argument.'),
        );
    }

    /**
     *
     * @return array
     */
    protected function getOptions() {
        return array(
            array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}
