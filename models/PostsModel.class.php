<?php

class Post extends Model {
    // get table info
    public function get_posts($limit, $offset) {
        try {
            parent::db_connect();
            $sql = "SELECT * FROM posts LIMIT ? OFFSET ?";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array($limit, $offset));
            var_dump($this->stmt);
            parent::db_drop_connection();
        }
        catch (Exception $e) {
            throw new Exception("Error while getting the posts in model" . $e->getMessage());
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