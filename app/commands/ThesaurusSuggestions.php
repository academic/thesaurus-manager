<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

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
    protected $description = 'Command description.';
    private $url = 'http://thesaurus.com/browse/';

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
        $this->url.=$word;
        $html = new Htmldom($this->url);
        $listDiv = $html->find('.relevancy-list', 0);
        for ($i = 0; $i <= 5; ++$i) {
            echo "\n\nLEVEL " . $i . "\n";
            $list = $listDiv->find('ul', $i);
            if ($list) {
                foreach ($list->find('li') as $element) {
                    $linkElement = $element->find('a', 0);
                    $linkHref = $linkElement->href;
                    $dataCategory = html_entity_decode($linkElement->getAttribute('data-category'));
                    echo $linkHref . "\n";
                }
            }
        }
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
