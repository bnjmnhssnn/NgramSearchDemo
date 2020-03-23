<?php
use StringMatching\CommonSubstrings;

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
    $processed = array_map(
        function($item) use ($search_string){
            $item->common_substrings = CommonSubstrings::find(
                trim(mb_strtolower($search_string)), 
                mb_strtolower($item->key), 
                (mb_strlen(trim($search_string)) < 3) ? 2 : 3 // reject very short common substrings
            );
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

        $sorted = sortBySubstrLen($filtered);

        $result_box = 
            '<div class="result-box">' 
                . join(array_map(
                    function($item) {
                        return '<div>' . $item->value . ' (' . substrLen($item->common_substrings) . ')</div>';
                    },
                    array_slice($sorted, 0, $max_results)
                )) .
            '</div>';
    }
    return json_encode([
        'html' => $result_box,
        'api_response_time' => round($api_response_time, 3) . 's',
        'api_query_time' => round($api_res->meta->duration, 3) . 's'
    ]);
}

function sortBySubstrLen(array $results) : array
{
    $get_sorter = function($i = 0) use (&$get_sorter) {
        return function($a, $b) use ($i, $get_sorter) {
            if (!isset($a->common_substrings[$i]) && !isset($b->common_substrings[$i])) {
                if (mb_strlen($a->value) === mb_strlen($b->value)) {
                    return strcasecmp($a->value, $b->value);
                }
                return (mb_strlen($a->value) < mb_strlen($b->value)) ? -1 : 1;   
            }
            if (!isset($a->common_substrings[$i])) {
                return 1;
            }
            if (!isset($b->common_substrings[$i])) {
                return -1;
            }
            if (count($a->common_substrings[$i]) === count($b->common_substrings[$i])) {
                $i++;
                return $get_sorter($i)($a, $b);
            }
            return (count($a->common_substrings[$i]) > count($b->common_substrings[$i])) ? -1 : 1;
        };
    };
    usort(
        $results,
        $get_sorter()  
    );
    return $results;
}

function substrLen(array $common_substrings) : string
{
    return join(', ', array_map(
        function($item) {
            return count($item);
        },
        $common_substrings
    ));
}