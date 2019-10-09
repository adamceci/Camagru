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

    protected function db_drop_connection(&$stmt) {
        $stmt->closeCursor();
        $stmt = null;
        $this->pdo = null;
    }
}