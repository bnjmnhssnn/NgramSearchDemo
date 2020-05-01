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
            echo buildResponse(json_decode($res->getBody()), $search_string, 10, $time);
            break;
        default:
            header("HTTP/1.1 500 Internal Server Error");
    }
}

function buildResponse(stdClass $api_res, string $search_string, int $max_results, float $api_response_time) : string
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
                    processApiData($api_res->data, $max_results)
                )) .
            '</div>';
    }
    return json_encode([
        'html' => $result_box,
        'api_response_time' => round($api_response_time, 3) . 's',
        'api_query_time' => round($api_res->meta->duration, 3) . 's'
    ]);
}

function processApiData(array $api_data, int $max_results) : array
{
    $api_data = array_map(
        function($item) {
            $item->chain_lengths = extractChainLengths($item->ngram_details);
            return $item;    
        },
        $api_data
    );  
    usort($api_data, getSorter());
    return array_slice($api_data, 0, $max_results);
}

function getSorter($i = 0) : Closure 
{
    return function($a, $b) use (&$sort_by_chain_lengths, $i) {
        if (isset($a->chain_lengths[$i]) && !isset($b->chain_lengths[$i])) {
            return -1;
        }
        if (!isset($a->chain_lengths[$i]) && isset($b->chain_lengths[$i])) {
            return 1;
        }
        if (!isset($a->chain_lengths[$i]) && !isset($b->chain_lengths[$i])) {
            return 0;
        }
        if($a->chain_lengths[$i] === $b->chain_lengths[$i]) {
            $i++;
            return getSorter($i)($a, $b);
        }
        return ($a->chain_lengths[$i] > $b->chain_lengths[$i]) ? -1 : 1;
    };
}

function extractNgramPositions(array $ngram_details) : array
{
    $positions = array_reduce(
        array_column($ngram_details, 'pos_in_key'),
        function($carry, $item) {
            return array_merge($carry, explode(',', $item));
        },
        []  
    );
    sort($positions);
    return $positions;
}

function extractChainLengths(array $ngram_details) : array
{
    $ngram_positions = extractNgramPositions($ngram_details);
    $chain_lengths = [];
    $curr = 0;
    for($i = 0; $i < count($ngram_positions); $i++) {
        if($i === 0 || $ngram_positions[$i] == ($ngram_positions[$i - 1] + 1)) {
            $curr++;
        } elseif ($curr > 0) {
            $chain_lengths[] = $curr; 
            $curr = 1;   
        }
    }
    if($curr > 0) {
        $chain_lengths[] = $curr;
    }
    rsort($chain_lengths);
    return $chain_lengths;
}

