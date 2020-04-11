<?php

function run(string $search_string, $http_client) : void
{
    try {
        $time_start = microtime(true);
        $res = $http_client->request(
            'GET', 
            SEARCH_API_URL . '/indexes/' . SEARCH_API_INDEX . '/query/' . urlencode($search_string), 
            [
                'auth' => [SEARCH_API_USER, SEARCH_API_PASS],
                'http_errors' => false
            ]
        );
        $time_end = microtime(true);
        $time = $time_end - $time_start;
    } catch (\Exception $e) {
        header("HTTP/1.1 500 Internal Server Error");
        return;   
    }
    switch ($res->getStatusCode()) {
        case 200:
            header("HTTP/1.1 200 OK");
            header("Content-type: application/json");
            echo processApiRes(json_decode($res->getBody()), $search_string, 10, $time);
            break;
        default:
            header("HTTP/1.1 500 Internal Server Error");
    }
}

function processApiRes(stdClass $api_res, string $search_string, int $max_results, float $api_response_time) : string
{
    if (empty($api_res->data)) {
        $result_box = 
            '<div class="result-box">Nothing found...</div>';
    } else {

        $result_box = 
            '<div class="result-box">' 
                . join(array_map(
                    function($item) {
                        return '<div>' . $item->value  . '</div>';
                    },
                    array_slice($api_res->data, 0, $max_results)
                )) .
            '</div>';
    }
    return json_encode([
        'html' => $result_box,
        'api_response_time' => round($api_response_time, 3) . 's',
        'api_query_time' => round($api_res->meta->duration, 3) . 's'
    ]);
}