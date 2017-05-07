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

            if (isset($_GET['page_num']) && isset($_GET['page_size'])) {
                $nodes = $treeService->getChildrenNodes(
                    $_GET['node_id'],
                    $_GET['language'],
                    isset($_GET['search_keyword']) ? $_GET['search_keyword'] : '',
                    $_GET['page_num'],
                    $_GET['page_size']
                );
            } else {
                $nodes = $treeService->getChildrenNodes(
                    $_GET['node_id'],
                    $_GET['language'],
                    isset($_GET['search_keyword']) ? $_GET['search_keyword'] : ''
                );
            }

            echo json_encode(['nodes' => $nodes, 'error' => '']);
        } else {
            $error = join("\n", $errors);
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

    if (!isset($params['node_id'])) {
        array_push($errors, 'Missing mandatory params');
    } else if (!is_numeric($params['node_id'])) {
        array_push($errors, 'Error: Param [node_id] must be a number!');
    }


    if (!isset($params['language'])) {
        array_push($errors, 'Missing mandatory params');
    } else if ('italian' != $params['language'] && 'english' != $params['language']) {
        array_push($errors, 'Error: Param [language] must be match with "italian" or "english" string!');
    }

//    isset($_GET['page_num']) && isset($_GET['page_size']
    if (isset($_GET['page_num']) && !is_numeric($_GET['page_num'])) {
        array_push($errors, 'Invalid page number request');
    }

    return $errors;
}
