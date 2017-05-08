<?php

namespace NestedRoles\bl;


use NestedRoles\bl\contracts\INodeTree;
use NestedRoles\dal\NodeTreeRepositoryImpl;

class NodeTreeService implements INodeTree {
    private $nodeRepository = null;

    public function __construct() {
        $this->nodeRepository = new NodeTreeRepositoryImpl();
    }

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
    public function getChildrenNodes($nodeId, $language, $search = null, $page = 0, $pageSize = 100) {
        $start = $page * $pageSize;

        if (isset($search) && !empty($search)) {
            return $this->nodeRepository->findByNodeIdAndLanguageAndPartialNodeName(
                $nodeId, $language, $search, $start, $pageSize);
        } else {
            return $this->nodeRepository->findByNodeIdAndLanguage($nodeId, $language, $start, $pageSize);
        }

    }

    /**
     * function to clean the input params, it removes spaces and tags from each param
     *
     * @param $data
     * @return array|string
     */
    private function cleanInputs($data) {
        $clean_input = Array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->cleanInputs($v);
            }
        } else {
            $clean_input = trim(strip_tags($data));
        }

        return $clean_input;
    }


    /**
     * Method to check if a $nodeId exist or not
     *
     * @param $nodeId
     * @return mixed
     */
    public function existNode($nodeId) {
        $node = $this->nodeRepository->findByNodeId($nodeId);

        if (isset($node) && !empty($node)) {
            return true;
        }

        return false;
    }
}