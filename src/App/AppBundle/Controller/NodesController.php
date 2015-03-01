<?php

namespace App\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Everyman\Neo4j\Traversal;
use Everyman\Neo4j\Relationship;
use Everyman\Neo4j\Client;
use Everyman\Neo4j\Index\NodeIndex;
use Symfony\Component\HttpFoundation\Request;
use App\AppBundle\Entity\Node;

class NodesController extends Controller
{
    protected $client;

    public function __construct(){

        $this->client = new Client('127.0.0.1');
    }

    public function indexAction()
    {
        return $this->render(
            'AppAppBundle:Nodes:index.html.twig'
        );
    }

    public function getSearchAction()
    {
        return $this->render(
            'AppAppBundle:Nodes:search.html.twig'
        );
    }

    public function postSearchAction(Request $request)
    {
        $word = strtolower($request->get('word'));
        $thesarusIndex = new NodeIndex($this->client, 'thesaurus');

        $matches = $thesarusIndex->query('approve:"1" && word:*' . urlencode($word) . '*');
        $results = array();
        foreach ($matches as $m) {
            $results[] = array('properties' => $m->getProperties(), 'id' => $m->getId());
        }

        return $this->render(
            'AppAppBundle:Nodes:search.html.twig',array(
                'results' => $results
            )
        );
    }

    public function getAddAction()
    {
        return $this->render(
            'AppAppBundle:Nodes:add.html.twig'
        );
    }

    public function postAddAction(Request $request)
    {
        $nodeEntity = new Node();

        $word1 = strtolower(urlencode($request->get('word1')));
        $word2 = strtolower(urlencode($request->get('word2')));
        $language = strtolower(urlencode($request->get('lang')));
        $level = (int) $request->get('level');

        $node1 = $nodeEntity->addNode($word1,$language);
        $node2 = $nodeEntity->addNode($word2,$language);

        $nodeEntity->addRelation($node1,$node2,$level);
        $nodeEntity->addRelation($node2,$node1,$level);

        return $this->redirect('/nodes/graph/'.$node1->getId());
    }

    public function getAddNodeAction(Request $request,$relatedId)
    {
        $nodeEntity = new Node();

        $word = strtolower($request->get('word1'));
        $node = $nodeEntity->addNode($word);
        if ($relatedId) {
            $nodeRelated = $this->client->getNode($relatedId);
            $level = (int) $request->get('level');
            $nodeEntity->addRelation($nodeRelated, $node, $level);
            $nodeEntity->addRelation($node, $nodeRelated, $level);
        }
        return json_encode(array("id" => $node->getId()));
    }

    public function postAddSynonymAction(Request $request)
    {
        $nodeEntity = new Node();
        $word1 = strtolower(urlencode($request->get('word1')));
        $word2 = strtolower(urlencode($request->get('word2')));
        $language = strtolower(urlencode($request->get('lang')));

        $node1 = $nodeEntity->addNode($word1, $language);
        $node2 = $nodeEntity->addNode($word2, $language);

        $nodeEntity->addRelation($node1, $node2, NULL, 'SYNONYM');
        $nodeEntity->addRelation($node2, $node1, NULL, 'SYNONYM');

        return $this->redirect('/nodes/graph/' . $node1->getId());
    }

    public function getGraphAction(Request $request, $id)
    {
        $client = $this->client;
        $node = $client->getNode($id);

        $traversal = new Traversal($client);
        $traversal->addRelationship('RELATED', Relationship::DirectionOut)
            ->setPruneEvaluator(Traversal::PruneNone)
            ->setReturnFilter(Traversal::ReturnAll)
            ->setMaxDepth(2);
        $nodes = $traversal->getResults($node, Traversal::ReturnTypeNode);

        $traversal2 = new Traversal($client);

        $traversal2->addRelationship('SYNONYM', Relationship::DirectionOut)
            ->setPruneEvaluator(Traversal::PruneNone)
            ->setReturnFilter(Traversal::ReturnAll)
            ->setMaxDepth(2);
        $nodesSynonym = $traversal2->getResults($node, Traversal::ReturnTypeNode);
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
        return $this->render('AppAppBundle:Nodes:graph-editor.html.twig', array(
            'nodes' => $nodes,
            'nodesSynonym' => $nodesSynonym,
            'relations' => $relations,
            'node' => $node,
            'approveLabel' => ($node->getProperty('approve') < 1 ? 'danger' : 'success')));
    }
}
