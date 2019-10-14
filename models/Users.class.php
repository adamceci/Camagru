<?php

require_once("models/Model.class.php");

class UsersModel extends Model {
    private function user_exist($email, $login) {
        try {
            parent::db_connect();
            $sql = "SELECT `login`, `email` FROM `users` WHERE LOWER(`email`)=? OR LOWER(`login`)=?";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute([$email, $login]);
            $arr = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            parent::db_drop_connection();
            if (!$arr) {
                return (0);
            } else {
                foreach ($arr as $row) {
                    if ($row["email"] == $email)
                        return (1);
                }
                return (2);
            }
        } catch (Exception $e) {
            throw new Exception("Error user_exist in User Model:" . $e->getMessage());
        }
    }

    public function create_user(array $kwuser_info) {
        try {
            $return_user_exist = $this->user_exist($kwuser_info["email"], $kwuser_info["login"]);
            if ($return_user_exist == 0) {
                parent::db_connect();
                $sql = "INSERT INTO `users` (email, `login`, `password`, `profile_pic`, `verif_hash`) VALUES (?,?,?,?,?)";
                $this->stmt = $this->pdo->prepare($sql);
                $this->stmt->execute([$kwuser_info['email'], $kwuser_info['login'], $kwuser_info['password'], $kwuser_info['profile_pic'], $kwuser_info['verif_hash']]);
                parent::db_drop_connection();
                return (0);
            } else {
                return ($return_user_exist);
            }
        } catch (Exception $e) {
            throw new Exception("Error create_user in User Model:" . $e->getMessage());
        }
    }
}