<?php

require_once("models/Model.class.php");

class User extends Model {

    private function user_email_exist($email) {
        try {
            parent::db_connect();
            $sql = "SELECT `email` FROM `users` WHERE LOWER(`email`)=?";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array($email));
            $arr = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            parent::db_drop_connection();
            if (!$arr) {
                return (0);
            } else {
                return (1);
            }
        } catch (Exception $e) {
            throw new Exception("Error user_email_exist in User Model:" . $e->getMessage());
        }
    }

    private function user_login_exist($login) {
        try {
            parent::db_connect();
            $sql = "SELECT `login` FROM `users` WHERE LOWER(`login`)=?";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array($login));
            $arr = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            parent::db_drop_connection();
            if (!$arr) {
                return (0);
            } else {
                return (1);
            }
        } catch (Exception $e) {
            throw new Exception("Error user_login_exist in User Model:" . $e->getMessage());
        }
    }

    private function user_login_or_email_exist($email, $login) {
        try {
            parent::db_connect();
            $sql = "SELECT `login`, `email` FROM `users` WHERE LOWER(`email`)=? OR LOWER(`login`)=?";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array($email, $login));
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
            throw new Exception("Error user_login_or_email_exist in User Model:" . $e->getMessage());
        }
    }

    public function create_user(array $kwuser_info) {
        try {
            $user_exist = $this->user_login_or_email_exist($kwuser_info["email"], $kwuser_info["login"]);
            if (!$user_exist) {
                parent::db_connect();
                $sql = "INSERT INTO `users` (email, `login`, `password`, `profile_pic`, `verif_hash`) VALUES (?,?,?,?,?)";
                $this->stmt = $this->pdo->prepare($sql);
                $this->stmt->execute(array($kwuser_info['email'], $kwuser_info['login'], $kwuser_info['password'], $kwuser_info['profile_pic'], $kwuser_info['verif_hash']));
                parent::db_drop_connection();
                return (1);
            } else if ($user_exist == 1) {
                return (EMAIL_EXISTS);
            } else {
                return (LOGIN_EXISTS);
            }
        } catch (Exception $e) {
            throw new Exception("Error create_user in User Model:" . $e->getMessage());
        }
    }
    
    public function activate_account($email, $hash) {
        try {
            parent::db_connect();
            $sql = "UPDATE `users` SET active=1 WHERE verif_hash=? AND email=?";
            $this->stmt = $this->pdo->prepare($sql);
            $return_value = $this->stmt->execute(array($hash, $email));
            parent::db_drop_connection();
            if ($return_value == FALSE) {
                return (USER_DONT_EXIST);
            } else {
                return (1);
            }
        } catch (Exception $e) {
            throw new Exception("Error create_user in User Model:" . $e->getMessage());
        }
    }
}