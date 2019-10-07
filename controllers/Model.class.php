<?php

class Model {
    protected $pdo;
    protected $stmt;

    protected function db_connect() {
        try {
            require("pass.php");
            $this->pdo = new PDO($dsn, $user, $password);
        }
        catch (PDOException $e) {
            throw new Exception ("Connection to database failed in Model");
        }
    }

    protected function execute_querry() {
        $this->stmt = $this->pdo->prepare($query);
        $this->stmt->execute();
        $this->db_drop_connection();
    }

    protected function db_drop_connection() {
        $this->stmt->closeCursor();
        $this->stmt = null;
        $this->pdo = null;
    }
}