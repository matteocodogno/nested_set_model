<?php

namespace NestedRoles\dal\contracts;

/**
 * Data access layer to retrieve node tree
 *
 * Interface NodeTreeRepository
 * @package NestedRoles\dal
 */
interface NodeTreeRepository {
    /**
     * Method to retrieve child nodes by parent nodeId and language
     *
     * @param $nodeId - parent ID
     * @param $language - language identifier
     * @param $start - how many nodes to skip
     * @param $pageSize - the size of the page to retrieve
     * @return array - nodes matching the given conditions
     */
	public function findByNodeIdAndLanguage($nodeId, $language, $start, $pageSize);

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
	public function findByNodeIdAndLanguageAndPartialNodeName($nodeId, $language, $searchTerm, $start, $pageSize);

    /**
     * Method to retrieve the number of child nodes of the current $nodeId
     *
     * @param $nodeId
     * @return mixed
     */
	public function countChildren($nodeId);

    /**
     * Method to retrieve a node by $nodeId
     *
     * @param $nodeId
     * @return mixed
     */
	public function findByNodeId($nodeId);
}