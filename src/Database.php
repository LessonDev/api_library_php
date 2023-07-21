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
        $this->name = 'api_library_php';
        $this->user = 'root';
        $this->password = 'root';
    }

    public function getConnection(): PDO
    {
        $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf8";

        //*Il sagit de la véritable émulation prepare. lorsque ATTR_EMULATE_PREPARES est mis a false
        //(il est true par default !)
        //Pour ceux qui débutent :oops:
        //
        //Avec True (de base) c'est un simulacre de prepare
        //1) le prepare est gardé au chaud par PDO
        //2) a l'exec PDO envoie le tout a MySql qui transmets a SQL ...
        //donc pour SQL tout se passe comme si prepare n'avait pas eu lieu
        //
        //Avec False c'est le vrais prépare
        //1) le prépare est envoyé a MySql qui le soumet a SQL le prépare est testé et pret a recevoir autant que voulu des exec !
        //*2) l'exec n'envoie donc que des variables qui sont sécurisées.

        return new PDO($dsn, $this->user, $this->password, [
            PDO::ATTR_EMULATE_PREPARES => false,
            //*Détermine si les valeurs numériques sont à convertir en chaînes de caractères lors de la récupération.
            //*Prend une valeur de type bool: true pour activer et false pour désactiver.
            PDO::ATTR_STRINGIFY_FETCHES => false //*true: "size": "10", false: "size": 10
        ]);
    }

}



