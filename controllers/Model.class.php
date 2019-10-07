<?php

class Model {
    private $db_conn;

    protected function db_connect() {
        try {
            require("pass.php");
            $this->db_con = new PDO($dsn, $user, $password);
        }
        catch (PDOException $e) {
            throw new Exception ("Connection to database failed in Model");
        }
    }
}