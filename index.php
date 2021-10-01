<?php
    include_once('config/database.php');
    include_once('config/controller.php');

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode('/', $uri);

    if ($uri[1] !== 'humans-api') {
        header("HTTP/1.1 404 Not Found");
        exit();
    }

    if ($uri[2] !== 'index.php') {
        header("HTTP/1.1 404 Not Found");
        exit();
    }

    $id = null;
    if (isset($uri[3])) {
        $id = (int) $uri[3];
    }

    $method = $_SERVER["REQUEST_METHOD"];

    $db = new Database();
    $conn = $db->getConnection();
    $controller = new Controller($conn, $method, $id);
    $controller->humansController();
?>
