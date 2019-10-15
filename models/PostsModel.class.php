<?php

class Post extends Model {
    // get table info
    // create line
    public function create_post(array $kwargs) {
        try {
            parrent::db_connect();
            $sql = "INSERT INTO posts (`user_id`, `image`, creation_date) VALUES (?, ?, ?)";
            $this->stmt = $this->dbo->prepare($sql);
            $this->stmt->execute(array($kwargs['user_id'], $kwargs['image']));
            parent::db_drop_connection();
        }
        catch (Exception $e) {
            throw new Exception("Error create_post in Posts Model : " . $e->getMessage());
        }
    }
    // delete line
}