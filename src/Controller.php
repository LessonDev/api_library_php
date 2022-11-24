<?php


class Controller
{
    protected $model;
    protected $table;

    public function __construct($model)
    {
        $this->table = $model->table;
        $this->model = $model;
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
        switch ($method) {
            //4.1 GET /author/{name}/books
            //cette api doit renvoyer la liste des livre disponible pour un auteur donné
            case "GET":
                if($books == 'books'){
                    //#### Input
                    // l'api accepte le parametre _order_ qui peut prendre les valeurs _id_ ou _title_
                    $param = (array) json_decode(file_get_contents("php://input"), true);
                    $data = $this->model->getBooksAuthor($id,$param);
                    //4.3 GET /author/{name}/books
                    if(!$data) {
                        http_response_code(404);
                        echo json_encode(["message" => "$this->table not found"]);
                        return;
                    }
                    elseif($data == "incorrect_parameters") {
                        http_response_code(422);
                        echo json_encode(["message" => "Incorrect parameter: must be 'title' book or 'id'"]);
                        return;
                    }
                    http_response_code(200);
                    echo json_encode($data);
                    return;
                }else{
                    //1.1 GET /books/{id}
                    //cette api doit renvoyer un livre demandé
                    $data = $this->model->get($id);
                    //1.3 GET /books/{id}
                    //Un livre au format JSON
                    if (!$data) {
                        http_response_code(404);
                        echo json_encode(["message" => "book not found"]);
                        return;
                    }
                    http_response_code(200);
                    echo json_encode($data);
                    return;
                }
            break;
            //5.1 PATCH /books/{id}
            case "PATCH":
                $current = $this->model->get($id);
                if(!$current){
                    http_response_code(404);
                    echo json_encode(["message" => "$this->table not found"]);
                    return;
                }
                $param = (array) json_decode(file_get_contents("php://input"), true);//Lit tout un fichier dans une chaîne
                $data = $this->model->updatePatch($current,$id,$param);

                //5.3 PATCH /books/{id}
                //Un livre au format JSON
                if(!$data){
                    http_response_code(422);
                    echo json_encode(["error" => "non update,no change detected"]);
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
                //6.1 PUT /books/{id}
                $current = $this->model->get($id);
                if(!$current){
                    http_response_code(404);
                    echo json_encode(["message" => "$this->table not found"]);
                    return;
                }
                $param = (array) json_decode(file_get_contents("php://input"), true);//Lit tout un fichier dans une chaîne
                $data = $this->model->updatePUT($id,$param);

                //6.3 PUT /books/{id}
                //Un livre au format JSON
                if(!$data){
                    http_response_code(422);
                    echo json_encode(["error" => "non update,no change detected"]);
                    return;
                }
                $getPath = str_replace("index.php", "$this->table/{$id}", $_SERVER['PHP_SELF']);
                http_response_code(201);
                echo json_encode([
                    "message" => "$this->table $id updated",
                    "GET" => "$getPath" //Le livre modifier (cf la sortie de l'api GET /books/{id} )
                ]);
                break;
            case "DELETE":
                //7.1 DELETE /books/{id}
                //7.1 DELETE /author/{id}
                $data = $this->model->delete($id);
                //7.3 DELETE /books/{id}
                //7.3 DELETE /author/{id}
                //Un livre au format JSON
                if(!$data){
                    http_response_code(404);
                    echo json_encode(["message" => "$this->table not found"]);
                    return;
                }
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
            //3.1 GET /books
            //cette API doit renvoyer la liste des livres dans le catalogue
            case "GET":
                //#### Input
                // l'api accepte le parametre _order_ qui peut prendre
                // les valeurs _author_ ou _title_ et triera les livres par auteur ou par titre
                $param = (array) json_decode(file_get_contents("php://input"), true);

                $data =  $this->model->getAll($param);
                //3.2 GET /books
                //Une liste au format JSON
                if(!$data) {
                    http_response_code(404);
                    echo json_encode(["message" => "$this->table not found"]);
                    return;
                }
                elseif($data == "incorrect_parameters") {
                    http_response_code(422);
                    echo json_encode(["message" => "Incorrect parameter: must be 'title' book or 'author'"]);
                    return;
                }
                http_response_code(200);
                echo json_encode($data);
                return;
                break;
            case "POST":
                //2.1 POST /books
                //cette api doit permettre de créer un livre

                //*php://input est un flux en lecture seule qui permet de lire des données brutes depuis le corps de la requête.
                //php://input n'est pas disponible avec enctype="multipart/form-data".
                //JSON => PHP json_decode = deserialize
                //PHP => JSON json_encode = serialize
                //*https://www.gekkode.com/developpement/php/php-json_encode-serialisation-des-objets-php-en-json/

                // Input
                // liste des parametres (tous sont obligatoires)
                // __title__, titre du livre
                //__author__, id de l'auteur
                $param = (array) json_decode(file_get_contents("php://input"), true);

                //*json_decode(string,true)//Récupère une chaîne encodée JSON et la convertit en une valeur de PHP.
                //*Lorsque ce paramètre vaut true, les objets JSON seront retournés comme tableaux associatifs ; lorsque ce

                $data = $this->model->create($param);
                //2.3 POST /books
                if (!$data) {
                    //*Le code de statut de réponse HTTP 422 Unprocessable Entity
                    // indique que le serveur a compris le type de contenu de la requête
                    // et que la syntaxe de la requête est correcte mais que
                    //* le serveur n'a pas été en mesure de réaliser les instructions demandées.
                    http_response_code(422);
                    echo json_encode(["error" => "non created"]);
                    return;
                }
                //cette api doit permettre de créer un livre 3
                $getPath = str_replace("index.php", "$this->table/{$data}", $_SERVER['PHP_SELF']);
                http_response_code(201);
                echo json_encode([
                    "message" => "$this->table created",
                    //Le livre crée (cf la sortie de l'api GET /books/{id} )
                    "GET" => "$getPath" //Le livre modifier (cf la sortie de l'api GET /books/{id} )
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