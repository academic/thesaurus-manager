<?php

use Artdarek\Neo4j\Facades\Neo4j as Neo4j;

class Node {

    /**
     * 
     * @param Everyman\Neo4j\Node $node1
     * @param Everyman\Neo4j\Node $node2
     * @param integer $level
     */
    static function addRelation($node1, $node2, $level) {
        $client = new Everyman\Neo4j\Client();
        $relation = $client->makeRelationship();
        $relation->setStartNode($node1)
                ->setEndNode($node2)
                ->setType('RELATED')
                ->setProperty('level', $level)
                ->save();
        return $relation;
    }

    /**
     * (If root node dosn't exists add a root node)
     * @return Everyman\Neo4j\Node
     */
    static function checkRoot() {
        $client = new Everyman\Neo4j\Client();
        $thesarusIndex = Node::getIndex($client);
        $word = Config::get('thesaurus.root_node_name');
        $root = $thesarusIndex->findOne("word", $word);
        if (empty($root)) {
            $root = Neo4j::makeNode();
            $root->setProperty('word', Config::get('thesaurus.root_node_name'))->save();
            $thesarusIndex->add($root, 'word', $word);
        }
        return $root;
    }

    /**
     * 
     * @param Everyman\Neo4j\Client $client
     * @return \Everyman\Neo4j\Index\NodeIndex
     */
    static function getIndex($client) {
        $thesarusIndex = new Everyman\Neo4j\Index\NodeIndex($client, 'thesaurus');
        return $thesarusIndex;
    }

    /**
     * necessary for firt time installation
     * @return type
     */
    static function createIndex() {
        $client = new Everyman\Neo4j\Client();
        $thesarusIndex = Node::getIndex($client);
        $thesarusIndex->save();
    }

    /**
     * Add new node and link it to root node 
     * @param string $word
     * @param Everyman\Neo4j\Node $root
     * @param Everyman\Neo4j\Client $client
     */
    static function addNode($word) {
        $client = new Everyman\Neo4j\Client();
        $thesaurusIndex = Node::getIndex($client);
        $thesaurus = $thesaurusIndex->findOne('word', $word);
        if (empty($thesaurus)) {
            $thesaurus = Neo4j::makeNode();
            $thesaurus->setProperty('word', $word)
                    ->save();
            $thesaurusIndex->add($thesaurus, 'word', $thesaurus->getProperty('word'));
            // Link to root node 
            $root = Node::checkRoot();
            $linkToRoot = $client->makeRelationship();
            $linkToRoot->setStartNode($thesaurus)
                    ->setEndNode($root)
                    ->setType('ROOT')
                    ->save();
        }
        return $thesaurus;
    }

    static function deleteNodeByValue($word) {
        $client = new Everyman\Neo4j\Client();
        $thesarusIndex = Node::getIndex($client);
        $thesaurus = $thesarusIndex->findOne("word", $word);
        return $thesaurus->delete();
    }

}
