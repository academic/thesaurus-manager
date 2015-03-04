<?php

namespace App\AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\AppBundle\Entity\Node;

class SampleDataCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('sampledata')
            ->setDescription('Create Sample Data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Adding sample data starting...</info>');
        $node = new Node('127.0.0.1');
        $root = $node->addNode("biology", "en", TRUE);
        foreach (array("bacteria", "virus", "microbe", "pathogen", "anatomy") as $word) {
            $thesaurus = $node->addNode($word, "en", TRUE);
            $node->addRelation($thesaurus, $root, 100);
            $node->addRelation($root, $thesaurus, 100);
        }

        $root2 = $node->addNode("virus", "en", TRUE);
        foreach (array("hiv", "ebola", "hepatitis b", "smallpox", "b19") as $word) {
            $thesaurus = $node->addNode($word, "en", TRUE);
            $node->addRelation($thesaurus, $root2, 100);
            $node->addRelation($root2, $thesaurus, 100);
        }

        $root3 = $node->addNode("bacteria", "en", TRUE);
        foreach (array("clostridium botulinum", "salmonella", "tetanus", "vibrio cholera", "chlamydia") as $word) {
            $thesaurus = $node->addNode($word, "en", TRUE);
            $node->addRelation($thesaurus, $root3, 100);
            $node->addRelation($root3, $thesaurus, 100);
        }

        $root4 = $node->addNode("web development", "en", TRUE);
        foreach (array("php", "nodejs", "ruby", "python", "javascript") as $word) {
            $thesaurus = $node->addNode($word, "en", TRUE);
            $node->addRelation($thesaurus, $root4, 100);
            $node->addRelation($root4, $thesaurus, 100);
        }

        $root5 = $node->addNode("archaeology", "en", TRUE);
        foreach (array("hittite", "paleontology", "prehistory", "excavation", "paleology") as $word) {
            $thesaurus = $node->addNode($word, "en", TRUE);
            $node->addRelation($thesaurus, $root5, 80);
            $node->addRelation($root5, $thesaurus, 80);
        }

        $output->writeln("\nAdded sample nodes and relations");
        $output->writeln("\nTry searching 'bacteria' from '/nodes/search'");

    }
}