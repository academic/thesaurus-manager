<?php

use Everyman\Neo4j\Traversal;
use Everyman\Neo4j\Relationship;

class NodesController extends BaseController {

    public function getIndex() {
        return View::make('nodes/index');
    }

    public function postSearch() {
        $word = Input::get('word');
        $client = new Everyman\Neo4j\Client();
        $thesarusIndex = new Everyman\Neo4j\Index\NodeIndex($client, 'thesaurus');
        $matches = $thesarusIndex->query('word:*' . $word . '*');
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
        $client = new Everyman\Neo4j\Client();
        $word = Input::get('word');
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
        $word1 = Input::get('word1');
        $word2 = Input::get('word2');
        $level = (int) Input::get('level');

        $node1 = Node::addNode($word1);
        $node2 = Node::addNode($word2);

        Node::addRelation($node1, $node2, $level);
        Node::addRelation($node2, $node1, $level);
        return Redirect::to('nodes/graph-editor/' . $node1->getId());
    }

    public function getGraphEditor($id = NULL) {
        $client = new Everyman\Neo4j\Client();
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
            $nodeWords[] = $tmp->getProperty("word");
        }
        $relations = array();
        foreach ($nodes as $n) {
            $rels = $n->getRelationships(array("RELATED"), Relationship::DirectionOut);
            /* @var $rel Everyman\Neo4j\Relationship */
            foreach ($rels as $rel) {
                $startNode = $rel->getStartNode();
                $endNode = $rel->getEndNode();
                $relArray['source'] = $startNode->getProperty("word");
                $relArray['target'] = $endNode->getProperty("word");
                $relArray["left"] = FALSE;
                $relArray["right"] = TRUE;
                if (in_array($startNode->getProperty("word"), $nodeWords) && in_array($endNode->getProperty("word"), $nodeWords)) {
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
