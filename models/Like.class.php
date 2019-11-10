<?php

require_once("models/Model.class.php");

class Like extends Model {
    public function get_post_nblikes($post_id) {
        try {
            parent::db_connect();
            $sql = "SELECT COUNT(`active`) as nb_likes
                    FROM `likes`
                    WHERE `post_id`=? AND `active`='1'";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array($post_id));
            $arr = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            parent::db_drop_connection();
            return ($arr);
        } catch (Exception $e) {
            throw new Exception("Error get_post_nblikes in Comment Model:" . $e->getMessage());
        }
    }

    public function is_active($user_id, $post_id) {
        try {
            parent::db_connect();
            $sql = "SELECT `active`
                    FROM `likes`
                    WHERE `post_id`=? AND `user_id`=? AND `active`='1'";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array($post_id, $user_id));
            $arr = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            parent::db_drop_connection();
            if ($arr) {
                return (1);
            } else {
                return (0);
            }
        } catch (Exception $e) {
            throw new Exception("Error is_active in Comment Model:" . $e->getMessage());
        }
    }

    public function exist($user_id, $post_id) {
        try {
            parent::db_connect();
            $sql = "SELECT `active`
                    FROM `likes`
                    WHERE `post_id`=? AND `user_id`=?";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array($post_id, $user_id));
            $arr = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            parent::db_drop_connection();
            if ($arr) {
                return (1);
            } else {
                return (0);
            }
        } catch (Exception $e) {
            throw new Exception("Error exist in Comment Model:" . $e->getMessage());
        }
    }

    public function toggle_like($user_id, $post_id) {
        try {
            if ($this->is_active($user_id, $post_id)) {
                parent::db_connect();
                $sql = "UPDATE `likes`
                        SET `active`='0'
                        WHERE `user_id`=? AND `post_id`=?";
                $this->stmt = $this->pdo->prepare($sql);
                $this->stmt->execute(array($user_id, $post_id));
                parent::db_drop_connection();
                return (2);
            } else {
                parent::db_connect();
                $sql = "UPDATE `likes`
                        SET `active`='1'
                        WHERE `user_id`=? AND `post_id`=?";
                $this->stmt = $this->pdo->prepare($sql);
                $this->stmt->execute(array($user_id, $post_id));
                parent::db_drop_connection();
                return (1);
            }
        } catch (Exception $e) {
            throw new Exception("Error toggle_like in Comment Model:" . $e->getMessage());
        }
    }

    public function create_like($user_id, $post_id) {
        try {
            parent::db_connect();
            $sql = "INSERT 
                    INTO `likes` (`user_id`, `post_id`, `active`) 
                    VALUES (?,?,'1')";
            $this->stmt = $this->pdo->prepare($sql);
            if ($this->stmt->execute(array($user_id, $post_id)) == FALSE) {
                throw new Exception("Error, couldn't insert into comments in create_like");
            }
            parent::db_drop_connection();
            return (1);
        } catch (Exception $e) {
            throw new Exception("Error create_like in Comment Model:" . $e->getMessage());
        }
    }
}