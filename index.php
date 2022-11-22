<?php

use Controller\Controller;

require_once './controller/Controller.php';

spl_autoload_register(function ($class) {
    $_class = str_replace("\\", "/", "/model/$class.php");
    require __DIR__ . "$_class";
});

set_error_handler("ErrorHandler::handleError");


set_exception_handler("ErrorHandler::handleException");

header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);


if ($parts[2] != "books" AND $parts[2] != "author") {
    http_response_code(404);
    echo json_encode(["message" => "Not Found"]);
    exit;
}
$id = $parts[3] ?? null;
$books = $parts[4] ?? null;

$modelName = ucfirst($parts[2]);

$model =  new $modelName();
$controller = new Controller($model);


$controller->processRequest($_SERVER["REQUEST_METHOD"], $id, $books );