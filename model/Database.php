<?php


class Database
{

    private $host;
    private $name;
    private $user;
    private $password;

    public function __construct()
    {
        $this->host = 'localhost';
        $this->name = 'library';
        $this->user = 'root';
        $this->password = 'root';
    }

    public function getConnection(): PDO
    {
        $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf8";

        return new PDO($dsn, $this->user, $this->password, [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false //true: "size": "10", false: "size": 10
        ]);
    }

}



