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
            echo processApiRes(json_decode($res->getBody()));
            break;
        default:
            header("HTTP/1.1 500 Internal Server Error");
    }
}

function processApiRes(stdClass $api_res) : string
{
    $data = array_map(
        function($item) {
            return [
                'raw_value' => $item->value,
                'ngrams_hit' => $item->ngrams_hit, 
                'html' => '<div>' . $item->value . '</div>'
            ];
        },
        $api_res->data
    ); 

    return json_encode([
        'data' => $data
    ]);
}