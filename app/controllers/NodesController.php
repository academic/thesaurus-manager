<?php

use Everyman\Neo4j\Traversal;
use Everyman\Neo4j\Relationship;

class NodesController extends BaseController {

    public function getIndex() {
        return View::make('nodes/index');
    }

    public function postSearch() {
        $word = strtolower(\Illuminate\Support\Facades\Input::get('word'));
        $client = new Everyman\Neo4j\Client(Config::get('database.connections.neo4j.default')['host']);
        $thesarusIndex = new Everyman\Neo4j\Index\NodeIndex($client, 'thesaurus');

        $matches = $thesarusIndex->query('word:*' . urlencode($word) . '*');
        $results = array();
        foreach ($matches as $m) {
            $results[] = array('properties' => $m->getProperties(), 'id' => $m->getId());
        }
        return View::make('nodes/search', array('results' => $results));
    }

    public function getSearch() {

        return View::make('nodes/search');
    }

    public function getAdd() {
        return View::make('nodes/add');
    }

    /**
     * ajax request from model on graph-editor page
     * @param int|NULL $relatedId
     * @return Everyman\Neo4j\Node
     */
    public function getAddnode($relatedId = NULL) {
        $client = new Everyman\Neo4j\Client(Config::get('database.connections.neo4j.default')['host']);
        $word = strtolower(Input::get('word'));
        $node = Node::addNode($word);
        if ($relatedId) {
            $nodeRelated = $client->getNode($relatedId);
            $level = (int) Input::get('level');
            Node::addRelation($nodeRelated, $node, $level);
            Node::addRelation($node, $nodeRelated, $level);
        }
        return json_encode(array("id" => $node->getId()));
    }

    public function postAdd() {
        $word1 = strtolower(urlencode(Input::get('word1')));
        $word2 = strtolower(urlencode(Input::get('word2')));
        $language = strtolower(urlencode(Input::get('language')));
        $level = (int) Input::get('level');

        $node1 = Node::addNode($word1, $language);
        $node2 = Node::addNode($word2, $language);

        Node::addRelation($node1, $node2, $level);
        Node::addRelation($node2, $node1, $level);
        return Redirect::to('nodes/graph-editor/' . $node1->getId());
    }

    public function getGraphEditor($id = NULL) {
        $client = new Everyman\Neo4j\Client(Config::get('database.connections.neo4j.default')['host']);
        $node = $client->getNode($id);
        $index = Node::getIndex($client);
        $traversal = new Everyman\Neo4j\Traversal($client);
        $traversal->addRelationship('RELATED', Relationship::DirectionOut)
                ->setPruneEvaluator(Traversal::PruneNone)
                ->setReturnFilter(Traversal::ReturnAll)
                ->setMaxDepth(2);
        $nodes = $traversal->getResults($node, Traversal::ReturnTypeNode);
        $nodeIds = array();
        foreach ($nodes as $tmp) {
            $nodeWords[] = urldecode($tmp->getProperty("word"));
        }
        $relations = array();
        foreach ($nodes as $n) {
            $rels = $n->getRelationships(array("RELATED"), Relationship::DirectionOut);
            /* @var $rel Everyman\Neo4j\Relationship */
            foreach ($rels as $rel) {
                $startNode = $rel->getStartNode();
                $endNode = $rel->getEndNode();
                $startWord = urldecode($startNode->getProperty("word"));
                $endWord = urldecode($endNode->getProperty("word"));
                $relArray['source'] = $startWord;
                $relArray['target'] = $endWord;
                $relArray["left"] = FALSE;
                $relArray["right"] = TRUE;
                if (in_array($startWord, $nodeWords) && in_array($endWord, $nodeWords)) {
                    $relations[] = $relArray;
                }
            }
        }
        return View::make('nodes/graph-editor', array(
                    'nodes' => $nodes,
                    'relations' => $relations,
                    'node' => $node));
    }

}
