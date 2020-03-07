<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../src/env.php';
require '../vendor/autoload.php';

switch($_SERVER['REQUEST_METHOD'] . parse_url($_SERVER['REQUEST_URI'])['path']) {
    case 'GET/':
        require '../src/index_handler.php';
        index_handler(new GuzzleHttp\Client());
        break;
    case 'GET/search':
        require '../src/search_handler.php';
        search_handler(urldecode($_GET['search_string']), new GuzzleHttp\Client()); 
        break; 
    default:
        header("HTTP/1.1 404 Not Found");
}
exit;






