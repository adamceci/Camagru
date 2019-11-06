<?php

require_once("models/Model.class.php");
require_once("models/User.class.php");
require_once("models/PostsModel.class.php");


class Comment extends Model {
    public function get_post_comments($post_id) {
        try {
            parent::db_connect();
            $sql = "SELECT `message`, users.login 
                    FROM `comments` INNER JOIN users 
                    WHERE `post_id`=? AND comments.user_id=users.user_id";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array($post_id));
            $arr = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            parent::db_drop_connection();
            return ($arr);
        } catch (Exception $e) {
            throw new Exception("Error user_login_exist in User Model:" . $e->getMessage());
        }
    }

    public function create_comment($user_id, $post_id, $msg) {
        try {
            parent::db_connect();
            $sql = "INSERT 
                    INTO `comments` (`user_id`, `post_id`, `message`) 
                    VALUES (?,?,?)";
            $this->stmt = $this->pdo->prepare($sql);
            if ($this->stmt->execute(array($user_id, $post_id, $msg)) == FALSE) {
                throw new Exception("Error, couldn't insert into comments in create_conment");
            }
            parent::db_drop_connection();
            return (1);
        } catch (Exception $e) {
            throw new Exception("Error create_user in User Model:" . $e->getMessage());
        }
    }
}