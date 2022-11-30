<?php

require_once 'Database.php';

class Model  extends  Database
{
    protected $connexion;
    protected $table;

    public function __construct()
    {
        parent::__construct();
        $this->connexion = $this->getConnection();
    }

    public function getTable(){
        return $this->table;
    }

    //7.2 DELETE /books/{id}
    //8.2 DELETE /author/{id}
    public function delete(string $id): int
    {
        $sql = "DELETE FROM {$this->table}
                WHERE id = :id";

        $stmt = $this->connexion->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}