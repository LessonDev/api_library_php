<?php

namespace Controller;

class Controller
{
    protected $model;
    protected $table;

    public function __construct($model)
    {
        $this->table = $model->table; //properties
        $this->model = $model; //methods
    }

    public function processRequest(string $method, ?string $id, ?string $books): void
    {

        if ($id) {

            $this->processResourceRequest($method, $id, $books);

        } else {

            $this->processCollectionRequest($method);

        }
    }



    private function processResourceRequest(string $method, string $id, $books): void
    {
        if($books == 'books'){
            //4. GET /author/{name}/books
            //cette api doit renvoyer la liste des livre disponible pour un auteur donné 1
            $data = (array) json_decode(file_get_contents("php://input"), true);
            $livre = $this->model->getBooksAuthor($id,$data);

            if(!$livre) {
                http_response_code(404);
                echo json_encode(["message" => "$this->table not found"]);
                return;
            } elseif($livre == "incorrect_order")
            {
                http_response_code(422);
                echo json_encode(["message" => "Incorrect order: must be 'name book' or 'id'"]);
                return;
            }

        }else{
            //1. GET /books/{id}
            //cette api doit renvoyer un livre demandé 1
            $livre = $this->model->get($id);
            if (!$livre) {
                http_response_code(404);
                echo json_encode(["message" => "book not found"]);
                return;
            }
    }

        switch ($method) {
            //1. GET /books/{id}
            //cette api doit renvoyer un live demandé 3
            case "GET":
                echo json_encode($livre);
                break;
            default:
                http_response_code(405);
                header("Allow: GET");
        }
    }

    private function processCollectionRequest(string $method): void
    {

        switch ($method) {
            //3. GET /books
            //cette API doit renvoyer la liste des livres dans le catalogue 1
            case "GET":
                $data = (array) json_decode(file_get_contents("php://input"), true);

                $livres =  $this->model->getAll($data);

                if(!$livres) {
                    http_response_code(404);
                    echo json_encode(["message" => "$this->table  not found"]);
                    return;
                } elseif($livres == "incorrect_order")
                {
                    http_response_code(422);
                    echo json_encode(["message" => "Incorrect order: must be 'name book' or 'author'"]);
                    return;
                }

                echo json_encode($livres);
                break;
            case "POST":
                //2. POST /books
                //cette api doit permettre de créer un livre 1
                 $data = (array) json_decode(file_get_contents("php://input"), true);

                $id = $this->model->create($data);
                if (!$id) {
                    http_response_code(422);
                    echo json_encode(["error" => "non created"]);
                    return;
                }

                //2. POST /books
                //cette api doit permettre de créer un livre 3

                $getPath = str_replace("index.php", "$this->table/{$id}", $_SERVER['PHP_SELF']);
                http_response_code(201);
                echo json_encode([
                    "message" => "$this->table created",
                    "GET" => "$getPath" //Le livre crée (cf la sortie de l'api GET /books/{id} )
                ]);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Method Not Allowed"]);
                header("Allow: GET, POST");
        }
    }

}