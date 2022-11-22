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


}