<?php

use Artdarek\Neo4j\Facades\Neo4j as Neo4j;

class Node {

    /**
     * 
     * @param Everyman\Neo4j\Node $node1
     * @param Everyman\Neo4j\Node $node2
     * @param integer $level
     */
    static function addRelation($node1, $node2, $level = NULL, $type = 'RELATED') {
        $client = new Everyman\Neo4j\Client(Config::get('database.connections.neo4j.default')['host']);
        $relation = $client->makeRelationship();
        $relation->setStartNode($node1)
                ->setEndNode($node2)
                ->setType($type);
        if ($level) {
            $relation->setProperty('level', $level);
        }
        $relation->save();
        return $relation;
    }

    /**
     * (If root node dosn't exists add a root node)
     * @return Everyman\Neo4j\Node
     */
    static function checkRoot() {
        $client = new Everyman\Neo4j\Client(Config::get('database.connections.neo4j.default')['host']);
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
    static function getIndex($client = NULL) {
        if (empty($client)) {
            $client = new Everyman\Neo4j\Client(Config::get('database.connections.neo4j.default')['host']);
        }
        $thesarusIndex = new Everyman\Neo4j\Index\NodeIndex($client, 'thesaurus');
        return $thesarusIndex;
    }

    /**
     * necessary for firt time installation
     * @return type
     */
    static function createIndex() {
        $client = new Everyman\Neo4j\Client(Config::get('database.connections.neo4j.default')['host']);
        $thesarusIndex = Node::getIndex($client);
        $thesarusIndex->save();
    }

    /**
     * Add new node and link it to root node 
     * @param string $word
     * @param string $lang
     * @param boolean $forceAdd  if TRUE don't check for role and add as approve
     */
    static function addNode($word, $lang = 'en', $forceAdd = FALSE) {
        $user = Sentry::getUser();
        //$admin = $user && $user->hasAccess('admin');

        /**
         * cli client may add without user information with forceAdd parameter
         */
        if (!$user || ( $user && !$user->hasAccess('canAdd') )) {
            if ($forceAdd === FALSE) {
                App::abort(401, 'Not authenticated');
            }
        }
        $word = strtolower($word);
        $client = new Everyman\Neo4j\Client(Config::get('database.connections.neo4j.default')['host']);
        $thesaurusIndex = Node::getIndex($client);
        $thesaurus = $thesaurusIndex->queryOne('word:"' . $word . '" AND lang:"' . $lang . '"');
        if (empty($thesaurus)) {
            $thesaurus = Neo4j::makeNode();
            $thesaurus->setProperty('word', $word)
                    ->setProperty('lang', $lang)
                    ->setProperty('approve', $forceAdd ? 0 : 1)
                    ->setProperty('userid', ($user ? $user->getId() : 0))
                    ->save();
            Node::addNodeIndex($thesaurusIndex, $thesaurus);
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

    static function addNodeIndex($index, $node) {
        $index->add($node, 'word', $node->getProperty('word'));
        $index->add($node, 'lang', $node->getProperty('lang'));
        $index->add($node, 'approve', $node->getProperty('approve'));
        $index->add($node, 'userid', $node->getProperty('userid'));
    }

    static function renewNodeIndex($index, $node) {
        $index->remove($node, 'word');
        $index->remove($node, 'lang');
        $index->remove($node, 'approve');
        $index->remove($node, 'userid');

        $index->add($node, 'word', $node->getProperty('word'));
        $index->add($node, 'lang', $node->getProperty('lang'));
        $index->add($node, 'approve', $node->getProperty('approve'));
        $index->add($node, 'userid', $node->getProperty('userid'));
    }

    static function deleteNodeByValue($word) {
        $word = strtolower($word);
        $client = new Everyman\Neo4j\Client(Config::get('database.connections.neo4j.default')['host']);
        $thesarusIndex = Node::getIndex($client);
        $thesaurus = $thesarusIndex->findOne("word", $word);
        return $thesaurus->delete();
    }

}
