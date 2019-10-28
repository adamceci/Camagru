<?php

require_once("models/Model.class.php");

class User extends Model {

    public function auth_user($login, $hash_password) {
        try {
            parent::db_connect();
            $sql = "SELECT `email` 
                    FROM `users` 
                    WHERE (LOWER(`email`)=? OR `login`=?)
                    AND `password`=?";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array($login, $login, $hash_password));
            $arr = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            parent::db_drop_connection();
            if (!$arr) {
                return (0);
            } else {
                return (1);
            }
        } catch (Exception $e) {
            throw new Exception("Error auth_exist in User Model:" . $e->getMessage());
        }
    }

    public function delete_user($login, $hash_password) {
        try {
            parent::db_connect();
            $sql = "DELETE
                    FROM `users` 
                    WHERE (LOWER(`email`)=? OR `login`=?)
                    AND `password`=?";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array($login, $login, $hash_password));
            $arr = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            parent::db_drop_connection();
            if (!$arr) {
                return (0);
            } else {
                return (1);
            }
        } catch (Exception $e) {
            throw new Exception("Error delete_user in User Model:" . $e->getMessage());
        }
    }

    private function user_email_exist($email) {
        try {
            parent::db_connect();
            $sql = "SELECT `email` 
                    FROM `users` 
                    WHERE LOWER(`email`)=?";
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

    public function get_user_login($login) {
        try {
            parent::db_connect();
            $sql = "SELECT `login` 
                    FROM `users` 
                    WHERE LOWER(`login`)=?";
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
            $sql = "SELECT `login`, `email` 
                    FROM `users` 
                    WHERE LOWER(`email`)=? 
                    OR LOWER(`login`)=?";
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
                $sql = "INSERT 
                        INTO `users` (`email`, `login`, `password`, `profile_pic`, `verif_hash`, `notification_email`) 
                        VALUES (?,?,?,?,?,?)";
                $this->stmt = $this->pdo->prepare($sql);
                if ($this->stmt->execute(array($kwuser_info['email'], $kwuser_info['login'], $kwuser_info['password'], $kwuser_info['profile_pic'], $kwuser_info['verif_hash'], $kwuser_info['email'])) == FALSE) {
                    throw new Exception("Error, couldn't insert into users in create_user");
                }
                parent::db_drop_connection();
                return (1);
            } else if ($user_exist == 1) {
                return (EMAIL_EXISTS);
            } else {
                return (LOGIN_EXISTS);
            }
        } catch (PDOException $e) {
            throw new Exception("Error create_user in User Model:" . $e->getMessage());
        }
    }
    
    public function activate_account($email, $hash) {
        try {
            parent::db_connect();
            $sql = "UPDATE `users` 
                    SET active=1
                    WHERE verif_hash=? 
                    AND LOWER(email)=?";
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

    public function get_info($valid_field) {
        try {
            parent::db_connect();
            $sql = "SELECT `login`, `email`, `user_id`, `profile_pic`, `notification_email`
                    FROM `users` 
                    WHERE (LOWER(`email`)=? OR LOWER(`login`)=?)
                    AND `active`=1";
            $this->stmt = $this->pdo->prepare($sql);
            $email = $valid_field;
            $login = $valid_field;
            $this->stmt->execute(array($email, $login));
            $arr = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            parent::db_drop_connection();
            if (!$arr) {
                return (FALSE);
            } else {
                return ($arr);
            }
        } catch (Exception $e) {
            throw new Exception("Error user_login_or_email_exist in User Model:" . $e->getMessage());
        }
    }

    public function update_login($new_login, $old_login) {
        try {
            parent::db_connect();
            $sql = "UPDATE `users` 
                    SET login=?
                    WHERE LOWER(login)=?";
            $this->stmt = $this->pdo->prepare($sql);
            $return_value = $this->stmt->execute(array($new_login, $old_login));
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