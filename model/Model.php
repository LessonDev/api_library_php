<?php

require_once 'Database.php';

class Model  extends  Database
{
    protected $connexion;

    public function __construct()
    {
        parent::__construct();
        $this->connexion = $this->getConnection();
    }

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