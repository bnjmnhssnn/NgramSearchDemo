<?php
use StringMatching\CommonSubstrings;

function search_handler(string $search_string, $http_client) : void
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
    $processed = array_map(
        function($item) use ($search_string){
            $item->common_substrings = CommonSubstrings::find(trim(mb_strtolower($search_string)), mb_strtolower($item->key));
            return $item;
        },
        $api_res->data 
    );
    $filtered = array_values(array_filter(
        $processed,
        function($item) {
            return !empty($item->common_substrings);
        }
    )); 
    if (empty($filtered)) {
        $result_box = 
            '<div class="result-box">Nothing found...</div>';
    } else {
        usort(
            $filtered,
            function($a, $b) {
                if (count($a->common_substrings[0]) === count($b->common_substrings[0])) {
                    return strcasecmp($a->value, $b->value);
                }
                return (count($a->common_substrings[0]) > count($b->common_substrings[0])) ? -1 : 1;
            }    
        );
        $result_box = 
            '<div class="result-box">' 
                . join(array_map(
                    function($item) {
                        return '<div>' . $item->value . ' (' . count($item->common_substrings[0]) . ')</div>';
                    },
                    array_slice($filtered, 0, $max_results)
                )) .
            '</div>';
    }
    return json_encode([
        'html' => $result_box,
        'api_response_time' => round($api_response_time, 3) . 's',
        'api_query_time' => round($api_res->meta->duration, 3) . 's'
    ]);
}