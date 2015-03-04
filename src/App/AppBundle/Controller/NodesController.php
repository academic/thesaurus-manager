<?php

namespace App\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Everyman\Neo4j\Traversal;
use Everyman\Neo4j\Relationship;
use Everyman\Neo4j\Client;
use Everyman\Neo4j\Index\NodeIndex;
use Symfony\Component\HttpFoundation\Request;
use App\AppBundle\Entity\Node;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class NodesController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render(
            'AppAppBundle:Nodes:index.html.twig'
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getSearchAction()
    {
        return $this->render(
            'AppAppBundle:Nodes:search.html.twig'
        );
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postSearchAction(Request $request)
    {
        /*throw exception if token is not validate*/
        if (!$this->isCsrfTokenValid('search_term', $request->get('_csrf_token'))) {
            throw new AccessDeniedException("Access denied!", 403);
        }
        $client = new Client($this->container->getParameter('neo4j.host'));
        $word = strtolower($request->get('word'));
        $thesarusIndex = new NodeIndex($client, 'thesaurus');
        $matches = $thesarusIndex->query('approve:"1" && word:*' . urlencode($word) . '*');
        $results = array();
        foreach ($matches as $m) {
            $results[] = array('properties' => $m->getProperties(), 'id' => $m->getId());
        }
        return $this->render(
            'AppAppBundle:Nodes:search.html.twig', array(
                'results' => $results
            )
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAddAction()
    {
        return $this->render(
            'AppAppBundle:Nodes:add.html.twig'
        );
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function postAddAction(Request $request)
    {
        /*throw exception if token is not validate*/
        if (!$this->isCsrfTokenValid('node_add', $request->get('_csrf_token'))) {
            throw new AccessDeniedException("Access denied!", 403);
        }
        $nodeEntity = new Node($this->container->getParameter('neo4j.host'));
        $word1 = strtolower(urlencode($request->get('word1')));
        $word2 = strtolower(urlencode($request->get('word2')));
        $language = strtolower(urlencode($request->get('lang')));
        $level = (int)$request->get('level');

        $node1 = $nodeEntity->addNode($word1, $language);
        $node2 = $nodeEntity->addNode($word2, $language);

        $nodeEntity->addRelation($node1, $node2, $level);
        $nodeEntity->addRelation($node2, $node1, $level);

        return $this->redirect($this->generateUrl("nodes_graph", array('id' => $node1->getId())));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function postAddSynonymAction(Request $request)
    {
        /*throw exception if token is not validate*/
        if (!$this->isCsrfTokenValid('add_synonym_node', $request->get('_csrf_token'))) {
            throw new AccessDeniedException("Access denied!", 403);
        }
        $nodeEntity = new Node($this->container->getParameter('neo4j.host'));
        $word1 = strtolower(urlencode($request->get('word1')));
        $word2 = strtolower(urlencode($request->get('word2')));
        $language = strtolower(urlencode($request->get('lang')));

        $node1 = $nodeEntity->addNode($word1, $language);
        $node2 = $nodeEntity->addNode($word2, $language);

        $nodeEntity->addRelation($node1, $node2, NULL, 'SYNONYM');
        $nodeEntity->addRelation($node2, $node1, NULL, 'SYNONYM');

        return $this->redirect($this->generateUrl("nodes_graph", array('id' => $node1->getId())));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Everyman\Neo4j\Exception
     * @throws \Exception
     */
    public function getGraphAction(Request $request, $id)
    {
        $client = new Client($this->container->getParameter('neo4j.host'));
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
