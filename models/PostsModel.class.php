<?php

require_once("Model.class.php");

class Post extends Model {
    // get table info
    public function get_posts($limit, $offset) {
        try {
            parent::db_connect();
            $sql = "SELECT * FROM posts ORDER BY post_id DESC LIMIT ? OFFSET ?";
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array((int)$limit, (int)$offset));
            $arr = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            // echo "array = ";
            // var_dump($arr);
            parent::db_drop_connection();
            return $arr;
        }
        catch (Exception $e) {
            throw new Exception("Error while getting the posts in model " . $e->getMessage());
        }
    }
    // create line into table
    public function create_post(array $kwargs) {
        try {
            parent::db_connect();
            $sql = "INSERT INTO posts (`user_id`, `image`) VALUES (?, ?)";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array($kwargs['user_id'], $kwargs['image']));
            parent::db_drop_connection();
        }
        catch (Exception $e) {
            throw new Exception("Error create_post in Posts Model : " . $e->getMessage());
        }
    }
    // delete line
}