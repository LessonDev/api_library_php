<?php
require_once 'Model.php';

class Author extends  Model
{
    protected $table = 'author';

    //4.2 GET /author/{name}/books
    public function getBooksAuthor(string $id, array $data)
    {
        // l'api accepte le parametre _order_ qui peut prendre les valeurs
        // _id_ ou _title_ et triera les livres selon leurs ID ou leurs Titre
        $order = $data['order'];

        //_{name}_ est le nom de l'auteur en minuscule et des "_" remplacent les espaces
        $name = str_replace("_"," ","$id");

        $sql = "SELECT books.id, books.title FROM {$this->table} RIGHT JOIN books ON {$this->table}.id = books.{$this->table} WHERE {$this->table}.name = :name ORDER BY books.$order ";

        $stmt = $this->connexion->prepare($sql);

        if(!$stmt) {
            return 'incorrect_parameters';
        }

        //*PDOStatement::bindValue — Associe une valeur à un paramètre
        //*Associe une valeur à un nom correspondant ou à un point d'interrogation (comme paramètre fictif) dans la requê
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->execute();

        $data = [];
        $i = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data["data"][$i]["id"] = $row["id"];
            $data["data"][$i]["type"] = "book";
            $data["data"][$i]["title"] = $row["title"];
            $i++;
        }

        return $data;
    }

}