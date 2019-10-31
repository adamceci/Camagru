<?php

require_once("Model.class.php");

class Post extends Model {

    // get number of posts
    public function get_nb_pages() {
        try {
            parent::db_connect();
            $sql = "SELECT count(*) FROM posts WHERE posted = 1";
            $nb_posts = $this->pdo->query($sql)->fetchAll();
            $nb_posts = (int)$nb_posts[0]["count(*)"];
            return (ceil($nb_posts / 6));
        }
        catch (Exception $e) {
            throw new Exception("Error while counting the number of pages in post model " . $e->getMessage());
        }
    }

    // get 6 by 6 posts
    public function get_index_posts($limit, $offset) {
        try {
            parent::db_connect();
            $sql = "SELECT * FROM posts WHERE posted = 1 ORDER BY posted_date DESC LIMIT ? OFFSET ?";
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array((int)$limit, (int)$offset));
            $arr = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            parent::db_drop_connection();
            return $arr;
        }
        catch (Exception $e) {
            throw new Exception("Error while getting the index posts in model " . $e->getMessage());
        }
    }

    // get 6 by 6 posts of certain user
    public function get_user_uploads() {
        try {
            parent::db_connect();
            $sql = "SELECT * FROM posts WHERE `user_id` = ? ORDER BY post_id DESC LIMIT 6 OFFSET 0";
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array((int)$_SESSION["current_user_user_id"]));
            $arr = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            parent::db_drop_connection();
            return $arr;
        }
        catch (Exception $e) {
            throw new Exception("Error while getting the index posts in model " . $e->getMessage());
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

    // change 'posted' to be true
    public function publish_post(array $kwargs) {
        try {
			parent::db_connect();
            $sql = "UPDATE posts SET posted = 1, posted_date = ? WHERE `image` = ?";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array($kwargs["posted_time"], basename($kwargs["toPubSrc"])));
            parent::db_drop_connection();
        }
        catch (Exception $e) {
            throw new Exception("Error while publishing the post in Posts Model" . $e->getMessage());
        }
    }

    // delete line
    public function delete_post($kwargs) {
        try {
            parent::db_connect();
            $sql = "DELETE FROM posts WHERE `image` = ?";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array(basename($kwargs["toDelSrc"])));
            parent::db_drop_connection();
        }
        catch (Exception $e) {
            throw new Ecxeption("Error while deleting post in Post Model : " . $e->getMessage());
        }
    }
}