<?php

class PostsModel extends Model {
    // get table info
    // create line
    public function create_post(array $test) {
        try {
            parrent::db_connect();
            $sql = "INSERT INTO posts (`user_id`, `image`, creation_date) VALUES (?, ?, ?)";
            $this->stmt = $this->dbo->prepare($sql);
            $this->stmt->execute(array($test['user_id'], $test['image'], $test['creation_date']));
            parent::db_drop_connection();
        }
        catch (Exception $e) {
            throw new Exception("Error create_post in Posts Model : " . $e->getMessage());
        }
    }
    // delete line
}