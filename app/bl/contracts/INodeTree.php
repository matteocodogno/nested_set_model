<?php

namespace NestedRoles\bl\contracts;

/**
 * Interface INodeTree
 * @package NestedRoles\app\bl\contracts
 */
interface INodeTree {
    /**
     * Method to retrieve the child nodes under the parent that has $nodeId
     *
     * @param $nodeId - ID of the parent node
     * @param $language - language identifier
     * @param string $search - search term to filter results
     * @param int $page - page number
     * @param int $pageSize - size of the page to retrieve
     * @return mixed
     */
    public function getChildrenNodes($nodeId, $language, $search, $page = 0, $pageSize = 100);
}