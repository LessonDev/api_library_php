<?php

spl_autoload_register(function ($class) {
    $_class = str_replace("\\", "/", "/src/$class.php");
    require __DIR__ . "$_class";
});

//Choisit la function utilisateur callback pour gérer les erreurs dans un script.
set_error_handler("ErrorHandler::handleError");

//Définit une function utilisateur de question exceptions
set_exception_handler("ErrorHandler::handleException");

header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

if ($parts[2] != "books" AND $parts[2] != "author") {
    http_response_code(404);
    echo json_encode(["message" => "Not Found"]);
    exit;
}
 
$id = $parts[3] ?? null;
$books = $parts[4] ?? null;
 
$modelName = ucfirst($parts[2]); //ucfirst — Met le premier caractère en majuscule

//Instansiation de classes
$model =  new $modelName();
$controller = new Controller($model);

//var_dump($_SERVER["REQUEST_METHOD"]); //GET ... POST ...
$controller->processRequest($_SERVER["REQUEST_METHOD"], $id, $books);