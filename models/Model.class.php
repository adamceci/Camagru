<?php

class Model {
    protected $pdo;
    protected $stmt;

    protected function db_connect() {
        try {
            require("config/database.php");
            $this->pdo = new PDO($dsn, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            throw new Exception ('Connection to database failed in Model:' . $e->getMessage() . '<br/>');
        }
    }

    protected function db_drop_connection() {
        $this->stmt->closeCursor();
        $this->stmt = null;
        $this->pdo = null;
    }
}