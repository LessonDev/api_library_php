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
            //5. PATCH /books/{id}
            case "PATCH":
                $livre = $this->model->get($id);
                $data = (array) json_decode(file_get_contents("php://input"), true);//Lit tout un fichier dans une chaîne
                $rows = $this->model->updatePatch($livre,$id,$data);

                if(!$rows){
                    http_response_code(422);
                    echo json_encode(["error" => "non update"]);
                    return;
                }
                $getPath = str_replace("index.php", "$this->table/{$id}", $_SERVER['PHP_SELF']);
                http_response_code(201);
                echo json_encode([
                    "message" => "$this->table $id updated",
                    "GET" => "$getPath" //Le livre modifier (cf la sortie de l'api GET /books/{id} )
                ]);
                break;
            case "PUT":
                $livre = $this->model->get($id);
                $data = (array) json_decode(file_get_contents("php://input"), true);//Lit tout un fichier dans une chaîne
                $rows = $this->model->updatePUT($livre,$id,$data);

                if(!$rows){
                    http_response_code(422);
                    echo json_encode(["error" => "non update"]);
                    return;
                }
                $getPath = str_replace("index.php", "$this->table/{$id}", $_SERVER['PHP_SELF']);
                http_response_code(201);
                echo json_encode([
                    "message" => "$this->table $id updated",
                    "GET" => "$getPath" //Le livre modifier (cf la sortie de l'api GET /books/{id} )
                ]);
            case "DELETE":
                $rows = $this->model->delete($id);
                http_response_code(200);
                echo json_encode([
                    "message" => "$this->table id = $id is deleted",
                ]);
                break;
            default:
                http_response_code(405);
                header("Allow: GET,PATCH,PUT,DELETE");
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


                //*php://input est un flux en lecture seule qui permet de lire des données brutes depuis le corps de la requête.
                //php://input n'est pas disponible avec enctype="multipart/form-data".
                //JSON => PHP json_decode = deserialize
                //PHP => JSON json_encode = serialize
                //*https://www.gekkode.com/developpement/php/php-json_encode-serialisation-des-objets-php-en-json/
                 $data = (array) json_decode(file_get_contents("php://input"), true);

                //*json_decode(string,true)//Récupère une chaîne encodée JSON et la convertit en une valeur de PHP.
                //*Lorsque ce paramètre vaut true, les objets JSON seront retournés comme tableaux associatifs ; lorsque ce

                $id = $this->model->create($data);
                if (!$id) {
                    //*Le code de statut de réponse HTTP 422 Unprocessable Entity
                    // indique que le serveur a compris le type de contenu de la requête
                    // et que la syntaxe de la requête est correcte mais que
                    //* le serveur n'a pas été en mesure de réaliser les instructions demandées.
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
                //*405 Method Not Allowed
                //Le code de statut de réponse HTTP 405 Method Not Allowed indique que
                // la méthode utilisée pour la requête est connue
                //* du serveur mais qu'elle n'est pas supportée par la ressource ciblée.
                http_response_code(405);
                echo json_encode(["message" => "Method Not Allowed"]);
                header("Allow: GET, POST");
        }
    }

}