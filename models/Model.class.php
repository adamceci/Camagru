<?php

class Model {
    protected $pdo;
    protected $stmt;

    protected function db_connect() {
        try {
            require("password.php");
            $this->pdo = new PDO($dsn, $user, $password);
        }
        catch (PDOException $e) {
            throw new Exception ("Connection to database failed in Model");
        }
    }

    protected function db_drop_connection() {
        $this->stmt->closeCursor();
        $this->stmt = null;
        $this->pdo = null;
    }
}