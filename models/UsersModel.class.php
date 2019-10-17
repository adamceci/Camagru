<?php

require_once("models/Model.class.php");

class User extends Model {
    private function user_exist($email) {
        try {
            parent::db_connect();
            $sql = "SELECT `login` FROM `users` WHERE `email`=?";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute([$email]);
            $arr = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            parent::db_drop_connection();
            if (!$arr) {
                return (0);
            } else {
                return (1);
            }
        } catch (Exception $e) {
            throw new Exception("Error user_exist in User Model:" . $e->getMessage());
        }
    }

    public function create_user(array $kwuser_info) {
        try {
            if (!($this->user_exist($kwuser_info["email"]))) {
                parent::db_connect();
                $sql = "INSERT INTO `users` (email, `login`, `password`, `profile_pic`) VALUES (?,?,?,?)";
                $this->stmt = $this->pdo->prepare($sql);
                $this->stmt->execute([$kwuser_info['email'], $kwuser_info['login'], $kwuser_info['password'], $kwuser_info['profile_pic']]);
                parent::db_drop_connection();
                return (1);
            } else {
                return (0);
            }
        } catch (Exception $e) {
            throw new Exception("Error create_user in User Model:" . $e->getMessage());
        }
    }
}