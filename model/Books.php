<?php

require_once 'Model.php';

class Books extends  Model
{
    public $table = 'books';

    //1.2 GET /books/{id}
    //#### Input
    //_{id}_ est l'identifiant du livre
    public function get(string $id)
    {
        $sql ="SELECT  books.id , books.name, author.id as ai FROM {$this->table} JOIN author ON books.author = author.id WHERE books.id = :id";

        $stmt = $this->connexion->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = [];
        $i = 0;

        while ($book = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data["data"][$i]["id"] = $book["id"];
            $data["data"][$i]["name"] = $book["name"];
            $data["data"][$i]["type"] = "book";

            $sql ="SELECT  author.id, author.name  FROM {$this->table} JOIN author ON {$this->table}.author = author.id ";
            $stmta = $this->connexion->query($sql);

            while ($author = $stmta->fetch(PDO::FETCH_ASSOC)) {
                if($author["id"] == $book["ai"]){
                    $data["data"][$i]["author"] = $author;
                }
            }
            $i++;
        }
        return $data;
    }

    //2.2 POST /books
    public function create(array $data): string
    {
        $sql = "INSERT INTO {$this->table} (name, author)
                VALUES (:name, :author)";

        $stmt = $this->connexion->prepare($sql);

        //liste des parametres (tous sont obligatoires)
        //* __title__, titre du livre
        //* __author__, id de l'auteur
        $stmt->bindValue(":name", $data["title"], PDO::PARAM_STR);
        $stmt->bindValue(":author", $data["author"], PDO::PARAM_STR);

        $stmt->execute();

        //*PDO::lastInsertId — Retourne l'identifiant de la dernière ligne insérée ou la valeur d'une séquence
        return $this->connexion->lastInsertId();
    }

    //3.2 GET /books
    public function getAll(array $data){

        // l'api accepte le parametre _order_ qui peut prendre les valeurs
        // _author_ ou _title_ et triera les livres par auteur ou par titre
        $order = $data['order'] ?? 'id';

        $sql ="SELECT books.id, books.name, author.id as ai FROM {$this->table} JOIN author ON books.author = author.id ORDER BY $order";

        $stmt = $this->connexion->prepare($sql);

        if(!$stmt) {
            return 'incorrect_parameters';
        }
        $stmt->bindValue(":order", $order, PDO::PARAM_STR);

        $stmt->execute();

        $data = [];
        $i = 0;

        while ($book = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data["data"][$i]["id"] = $book["id"];
            $data["data"][$i]["name"] = $book["name"];

            $data["data"][$i]["type"] = "book";

            $sql ="SELECT  author.id, author.name  FROM {$this->table} JOIN author ON books.author = author.id ";
            $stmta = $this->connexion->query($sql);

            while ($author = $stmta->fetch(PDO::FETCH_ASSOC)) {
                 if($author["id"] == $book["ai"]){
                     $data["data"][$i]["author"] = $author;
                }
            }
            $i++;
        }

        return $data;
    }

    //5.2 PATCH /books/{id}
    public function updatePatch(array $current, string $id, array $new): int
    {
        $sql = "UPDATE {$this->table}
                SET name = :name, author = :author
                WHERE id = :id";

        $stmt = $this->connexion->prepare($sql);

        $stmt->bindValue(":name", $new["title"] ?? $current["data"][0]["name"], PDO::PARAM_STR);
        $stmt->bindValue(":author", $new["author"] ?? intval($current["data"][0]["author"]["id"]), PDO::PARAM_STR);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    //6.2 PUT /books/{id}
    public function updatePUT(array $current, string $id, array $new): int
    {
        $sql = "UPDATE {$this->table}
                SET name = :name, author = :author
                WHERE id = :id";

        $stmt = $this->connexion->prepare($sql);

        $stmt->bindValue(":name", $new["title"] , PDO::PARAM_STR);
        $stmt->bindValue(":author", $new["author"] , PDO::PARAM_STR);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }



}