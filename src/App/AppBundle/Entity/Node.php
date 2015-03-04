<?php
namespace App\AppBundle\Entity;

use Everyman\Neo4j\Client;
use Everyman\Neo4j\Index\NodeIndex;

class Node
{
    protected $client;
    protected $root_node_name;

    public function __construct($neoHost)
    {
        $this->client = new Client($neoHost);
        $this->root_node_name = "Thesaurus_Root_Node";
    }

    /**
     * @param $node1
     * @param $node2
     * @param null $level
     * @param string $type
     * @return \Everyman\Neo4j\Relationship
     * @throws \Everyman\Neo4j\Exception
     */
    public function addRelation($node1, $node2, $level = NULL, $type = 'RELATED')
    {
        $relation = $this->client->makeRelationship();
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
     * @return \Everyman\Neo4j\Node|\Everyman\Neo4j\PropertyContainer
     * @throws \Everyman\Neo4j\Exception
     */
    public function checkRoot()
    {
        $thesarusIndex = $this->getIndex();
        $word = $this->root_node_name;
        $root = $thesarusIndex->findOne("word", $word);
        if (empty($root)) {
            $root = $this->client->makeNode();
            $root->setProperty('word', $this->root_node_name)->save();
            $thesarusIndex->add($root, 'word', $word);
        }
        return $root;
    }

    /**
     * @return NodeIndex
     */
    public function getIndex()
    {

        $thesarusIndex = new NodeIndex($this->client, 'thesaurus');
        return $thesarusIndex;
    }

    public function createIndex()
    {

        $thesarusIndex = $this->getIndex($this->client);
        $thesarusIndex->save();
    }

    /**
     * @param $word
     * @param string $lang
     * @param bool $forceAdd
     * @return \Everyman\Neo4j\Node|\Everyman\Neo4j\PropertyContainer
     * @throws \Everyman\Neo4j\Exception
     */
    public function addNode($word, $lang = 'en', $forceAdd = FALSE)
    {
        //$user = Sentry::getUser();
        $word = strtolower($word);

        $thesaurusIndex = Node::getIndex($this->client);
        $thesaurus = $thesaurusIndex->queryOne('word:"' . $word . '" AND lang:"' . $lang . '"');
        $approvalState = $forceAdd ? 1 : 0;
        //$userId = $user ? $user->getId() : 0;
        $userId = 0;
        if (empty($thesaurus)) {
            $thesaurus = $this->client->makeNode();
            $thesaurus->setProperty('word', $word)
                ->setProperty('lang', $lang)
                ->setProperty('approve', $approvalState)
                ->setProperty('userid', $userId)
                ->save();
            $this->addNodeIndex($thesaurusIndex, $thesaurus);
            // Link to root node
            $root = $this->checkRoot();
            $linkToRoot = $this->client->makeRelationship();
            $linkToRoot->setStartNode($thesaurus)
                ->setEndNode($root)
                ->setType('ROOT')
                ->save();
        }
        return $thesaurus;
    }

    /**
     * @param $index
     * @param $node
     */
    public function addNodeIndex($index, $node)
    {
        $index->add($node, 'word', $node->getProperty('word'));
        $index->add($node, 'lang', $node->getProperty('lang'));
        $index->add($node, 'approve', $node->getProperty('approve'));
        $index->add($node, 'userid', $node->getProperty('userid'));
    }

    /**
     * @param $index
     * @param $node
     */
    public function renewNodeIndex($index, $node)
    {
        $index->remove($node, 'word');
        $index->remove($node, 'lang');
        $index->remove($node, 'approve');
        $index->remove($node, 'userid');
        $index->add($node, 'word', $node->getProperty('word'));
        $index->add($node, 'lang', $node->getProperty('lang'));
        $index->add($node, 'approve', $node->getProperty('approve'));
        $index->add($node, 'userid', $node->getProperty('userid'));
    }

    /**
     * @param $word
     * @return \Everyman\Neo4j\PropertyContainer
     */
    public function deleteNodeByValue($word)
    {
        $word = strtolower($word);

        $thesarusIndex = $this->getIndex($this->client);
        $thesaurus = $thesarusIndex->findOne("word", $word);
        return $thesaurus->delete();
    }
}