<?php
function search_handler(string $search) : void
{
    header("HTTP/1.1 200 OK");
    header("Content-type: application/json");
    echo json_encode(
        [
            'foo' => 'bar'
        ]
    );
}