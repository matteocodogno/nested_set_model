<?php

namespace NestedRoles\dal;

use NestedRoles\dal\contracts\NodeTreeRepository;

class NodeTreeRepositoryImpl extends Connection implements NodeTreeRepository {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Method to retrieve child nodes by parent nodeId and language
     *
     * @param $nodeId - parent ID
     * @param $language - language identifier
     * @param $start - how many nodes to skip
     * @param $pageSize - the size of the page to retrieve
     * @return array - nodes matching the given conditions
     */
    public function findByNodeIdAndLanguage($nodeId, $language, $start, $pageSize) {
        $query = "SELECT node.idNode, ntn.nodeName
                    FROM (
                      SELECT Child.*
                      FROM node_tree AS Child, node_tree AS Parent
                      WHERE
                        Child.level = Parent.level + 1
                        AND Child.iLeft > Parent.iLeft
                        AND Child.iRight < Parent.iRight
                        AND Parent.iLeft = (SELECT iLeft FROM node_tree WHERE idNode = ".$nodeId.")
                    ) as node
                      JOIN node_tree_names as ntn ON ntn.idNode = node.idNode && ntn.language = '".$language."'
                      LIMIT ".$start.",".$pageSize;


        return $this->executeQueryAndMapDto($query);
    }

    /**
     * Method to retrieve child nodes by parent nodeId and language
     *
     * @param $nodeId - parent ID
     * @param $language - language identifier
     * @param $searchTerm - search term to filter result
     * @param $start - how many nodes to skip
     * @param $pageSize - the size of the page to retrieve
     * @return array - nodes matching the given conditions
     */
    public function findByNodeIdAndLanguageAndPartialNodeName($nodeId, $language, $searchTerm, $start, $pageSize) {
        $query = "SELECT node.idNode, ntn.nodeName
                    FROM (
                      SELECT Child.*
                      FROM node_tree AS Child, node_tree AS Parent
                      WHERE
                        Child.level = Parent.level + 1
                        AND Child.iLeft > Parent.iLeft
                        AND Child.iRight < Parent.iRight
                        AND Parent.iLeft = (SELECT iLeft FROM node_tree WHERE idNode = ".$nodeId.")
                    ) as node
                      JOIN node_tree_names as ntn ON ntn.idNode = node.idNode && ntn.language = '".$language."'
                      WHERE LOWER(ntn.nodeName) LIKE LOWER('%".$searchTerm."%')
                      LIMIT ".$start.",".$pageSize;

        return $this->executeQueryAndMapDto($query);
    }

    private function executeQueryAndMapDto($query) {
        $nodes = [];

        if ($result = $this->conn->query($query)) {
            while ($row = $result->fetch_assoc()) {
                array_push($nodes, [
                    'node_id' => $row['idNode'],
                    'name' => $row['nodeName'],
                    'children' => $this->countChildren($row['idNode'])
                ]);
            }
        }

        $result->close();

        return $nodes;
    }

    /**
     * Method to retrieve the number of child nodes of the current $nodeId
     *
     * @param $nodeId
     * @return mixed
     */
    public function countChildren($nodeId) {
        $count = 0;
        $query = "SELECT COUNT(*)
                      FROM node_tree AS Child, node_tree AS Parent
                      WHERE
                        Child.level = Parent.level + 1
                        AND Child.iLeft > Parent.iLeft
                        AND Child.iRight < Parent.iRight
                        AND Parent.iLeft = (SELECT iLeft FROM node_tree WHERE idNode = ".$nodeId.")";

        if ($result = $this->conn->query($query)) {
            $count = $result->fetch_row();
        }

        return $count;
    }

    /**
     * Method to retrieve a node by $nodeId
     *
     * @param $nodeId
     * @return mixed
     */
    public function findByNodeId($nodeId) {
        $node = null;
        $query = "SELECT iLeft FROM node_tree WHERE idNode = ".$nodeId;

        if ($result = $this->conn->query($query)) {
            $node = $result->fetch_assoc();
        }

        return $node;
    }
}
