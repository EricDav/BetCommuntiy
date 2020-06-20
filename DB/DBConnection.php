<?php

class PDOConnection {
    public $pdo;
    public $dbConfig;
    public function __construct() {
        $this->pdo = null;
        $this->dbConfig = DBConfig::dbConfig[Enviroment::getEnv()];
    }

    public function open() {
        try {
            $options = [
                PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
            ];

            $dbConfig = $this->dbConfig;
            $pdo = new PDO('mysql:host=' . $dbConfig['host'] . ";port=" . $dbConfig['port'] . ';dbname=' . $dbConfig['database'], 
            $dbConfig['user'], $dbConfig['password'], $options);

            $this->pdo = $pdo;
        } catch(Exception $e) {
            // TODO 
            // Determines what to do in catch of open 
            var_dump($e->getMessage());
        }
    }

    public function close() {
        $this->pdo = null;
    }
}
?>
