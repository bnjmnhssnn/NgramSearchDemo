<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$request = parse_url($_SERVER['REQUEST_URI']);

switch($_SERVER['REQUEST_METHOD'] . $request['path']) {
    case 'GET/':
        require '../src/index_handler.php';
        index_handler();
        break;
    case 'GET/search':
        require '../src/search_handler.php';
        search_handler($_GET['search']); 
        break; 
    default:
        header("HTTP/1.1 404 Not Found");
        exit;
}






