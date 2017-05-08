<?php

require __DIR__.'/../vendor/autoload.php';

// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'),true);

$error = '';

switch ($method) {
    case 'GET':
        $errors = checkInputParams($_GET);
        if (empty($errors)) {
            $treeService = new \NestedRoles\bl\NodeTreeService();

            $nodes = $treeService->getChildrenNodes(
                $_GET['node_id'],
                $_GET['language'],
                isset($_GET['search_keyword']) ? $_GET['search_keyword'] : '',
                isset($_GET['page_num']) ? $_GET['page_num'] : 0,
                isset($_GET['page_size']) ? $_GET['page_size'] : 100
            );

            echo json_encode(['nodes' => $nodes, 'error' => '']);
        } else {
            $error = join(" - ", $errors);
        }

        break;
    case 'PUT':
    case 'POST':
    case 'DELETE':
        $error = 'HTTP method not supported';
}

// if something goes wrong return the error messages
if ($error != '') {
    echo json_encode(['nodes' => [], 'error' => $error]);
}

/**
 * function to check the input params
 *
 * @param $params
 * @return array - error list
 */
function checkInputParams($params) { // TODO: extract into validation class
    $errors = [];
    $nodeService = new \NestedRoles\bl\NodeTreeService();

    if (!isset($params['node_id'])) {
        array_push($errors, 'Missing mandatory params');
    } else if (!is_numeric($params['node_id'])) {
        array_push($errors, 'Error: Param [node_id] must be a number!');
    } else if (!$nodeService->existNode($params['node_id'])) {
        array_push($errors, 'Invalid Node ID');
    }


    if (!isset($params['language'])) {
        array_push($errors, 'Missing mandatory params');
    } else if ('italian' != $params['language'] && 'english' != $params['language']) {
        array_push($errors, 'Error: Param [language] must be match with "italian" or "english" string!');
    }

    if (isset($_GET['page_num']) && !is_numeric($_GET['page_num'])) {
        array_push($errors, 'Invalid page number request');
    }

    if (isset($_GET['page_size']) &&
        (!is_numeric($_GET['page_size']) || $_GET['page_size'] < 0 || $_GET['page_size'] > 1000 )) {

        array_push($errors, 'Invalid page size request');
    }

    return $errors;
}
