<?php
function search_handler(string $search_string, $http_client) : void
{
    try {
        $res = $http_client->request(
            'GET', 
            SEARCH_API_URL . '/' . SEARCH_API_INDEX . '/query/' . $search_string, 
            [
                //'auth' => ['user', 'pass'],
                'http_errors' => false
            ]
        );
    } catch (\Exception $e) {
        header("HTTP/1.1 500 Internal Server Error");
        return;   
    }
    switch ($res->getStatusCode()) {
        case 200:
            header("HTTP/1.1 200 OK");
            header("Content-type: application/json");
            echo processApiRes(json_decode($res->getBody()), 8);
            break;
        default:
            header("HTTP/1.1 500 Internal Server Error");
    }
}

function processApiRes(stdClass $api_res, int $max_results) : string
{
    $result_box = 
        '<div class="result-box">' 
            . join(array_map(
                function($item) {
                    return '<div>' . $item->value . '</div>';
                },
                array_slice($api_res->data, 0, $max_results)
            )) .
        '</div>';

    return json_encode([
        'html' => $result_box
    ]);
}