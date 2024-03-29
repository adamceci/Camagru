<?php

require_once("models/Model.class.php");

class User extends Model {

    public function auth_user($login, $hash_password) {
        try {
            parent::db_connect();
            $sql = "SELECT `email` 
                    FROM `users` 
                    WHERE (LOWER(`email`)=? OR `login`=?) AND `active`=1
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

    public function get_notification_email($login) {
        try {
            parent::db_connect();
            $sql = "SELECT `notification_email` 
                    FROM `users` 
                    WHERE (LOWER(`email`)=? OR LOWER(`login`)=? OR `user_id`=?) AND `active`='1'";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array($login, $login, $login));
            $arr = $this->stmt->fetch(PDO::FETCH_ASSOC);
            parent::db_drop_connection();
            return ($arr);
        } catch (Exception $e) {
            throw new Exception("Error user_email_exist in User Model:" . $e->getMessage());
        }
    }

    public function get_notification_active($login) {
        try {
            parent::db_connect();
            $sql = "SELECT `notification_active` 
                    FROM `users` 
                    WHERE (LOWER(`email`)=? OR LOWER(`login`)=? OR `user_id`=?) AND `active`='1'";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array($login, $login, $login));
            $arr = $this->stmt->fetch(PDO::FETCH_ASSOC);
            parent::db_drop_connection();
            return ($arr);
        } catch (Exception $e) {
            throw new Exception("Error get_notification_email in User Model:" . $e->getMessage());
        }
    }

    public function get_email($login) {
        try {
            parent::db_connect();
            $sql = "SELECT `email` 
                    FROM `users` 
                    WHERE (LOWER(`email`)=? OR LOWER(`login`)=?) AND `active`='1'";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array($login, $login));
            $arr = $this->stmt->fetch(PDO::FETCH_ASSOC);
            parent::db_drop_connection();
            return ($arr);
        } catch (Exception $e) {
            throw new Exception("Error user_email_exist in User Model:" . $e->getMessage());
        }
    }

    public function get_user_id($login) {
        try {
            parent::db_connect();
            $sql = "SELECT `user_id` 
                    FROM `users` 
                    WHERE LOWER(`login`)=?  AND `active`='1'";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array($login));
            $arr = $this->stmt->fetch();
            parent::db_drop_connection();
            return $arr;
        } catch (Exception $e) {
            throw new Exception("Error user_login_exist in User Model:" . $e->getMessage());
        }
    }

    public function user_login_or_email_exist($email, $login) {
        try {
            parent::db_connect();
            $sql = "SELECT `login`, `email` 
                    FROM `users` 
                    WHERE ((LOWER(`email`)=? OR LOWER(`login`=?))
                    AND `active`='1') OR ((LOWER(`email`)=? AND LOWER(`login`=?)) AND `active`='1')";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array($email, $login, $email, $login));
            $arr = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            parent::db_drop_connection();
            if (!$arr) {
                return (0);
            } else {
                $return_value = 0;
                if ($arr[0]["email"] == $email)
                    $return_value++;
                if ($arr[0]["login"] == $login)
                    $return_value += 2;
                return ($return_value);
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
            } else if ($user_exist == 2) {
                return (LOGIN_EXISTS);
            } else {
                return (EMAIL_EXISTS | LOGIN_EXISTS);
            }
        } catch (Exception $e) {
            throw new Exception("Error create_user in User Model:" . $e->getMessage());
        }
    }

    public function remove_inactive_accounts($email, $login) {
        try {
            parent::db_connect();
            $sql = "DELETE FROM `users` 
                    WHERE (LOWER(`email`)=? OR LOWER(`login`)=?)
                    AND `active`='0'";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute(array($email, $login));
            parent::db_drop_connection();
            return (1);
        } catch (Exception $e) {
            throw new Exception("Error create_user in User Model:" . $e->getMessage());
        }
    }

    public function activate_account($email, $login, $hash) {
        try {
            parent::db_connect();
            $sql = "UPDATE `users` 
                    SET active=1
                    WHERE verif_hash=? 
                    AND LOWER(email)=? OR LOWER(`login`)=?";
            $this->stmt = $this->pdo->prepare($sql);
            $return_value = $this->stmt->execute(array($hash, $email, $login));
            parent::db_drop_connection();
            if ($return_value == FALSE) {
                return (USER_DONT_EXIST);
            } else {
                $this->remove_inactive_accounts($email, $login);
                return (1);
            }
        } catch (Exception $e) {
            throw new Exception("Error activate_account in User Model:" . $e->getMessage());
        }
    }

    public function get_info($valid_field) {
        try {
            parent::db_connect();
            $sql = "SELECT `login`, `email`, `user_id`, `profile_pic`, `notification_email`, `date_of_creation`, `notification_active`
                    FROM `users` 
                    WHERE (LOWER(`email`)=? OR LOWER(`login`)=?)
                    AND `active`='1'";
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
            throw new Exception("Error get_info in User Model:" . $e->getMessage());
        }
    }

    public function update_hash($login, $hash) {
        try {
            parent::db_connect();
            $sql = "UPDATE `users`
                    SET `verif_hash`=?
                    WHERE (LOWER(`login`)=? OR LOWER(`login`)=?) AND `active`=1";
            $this->stmt = $this->pdo->prepare($sql);
            $return_value = $this->stmt->execute(array($hash, $login, $login));
            parent::db_drop_connection();
            if ($return_value == FALSE) {
                return (USER_DONT_EXIST);
            } else {
                return (1);
            }
        } catch (PDOException $e) {
            throw new Exception("Error update_hash in User Model:<br/>" . $e->getMessage());
        }
    }

    public function update_login($new_login, $old_login) {
        try {
            parent::db_connect();
            $sql = "UPDATE `users`
                    SET `login`=?
                    WHERE LOWER(`login`)=?";
            $this->stmt = $this->pdo->prepare($sql);
            $return_value = $this->stmt->execute(array($new_login, $old_login));
            parent::db_drop_connection();
            if ($return_value == FALSE) {
                return (USER_DONT_EXIST);
            } else {
                return (1);
            }
        } catch (PDOException $e) {
            throw new Exception("Error update_login in User Model:<br/>" . $e->getMessage());
        }
    }

    public function update_profile_pic($new_pic, $login) {
        try {
            parent::db_connect();
            $sql = "UPDATE `users`
                    SET `profile_pic`=?
                    WHERE LOWER(`login`)=?";
            $this->stmt = $this->pdo->prepare($sql);
            $return_value = $this->stmt->execute(array($new_pic, $login));
            parent::db_drop_connection();
            if ($return_value == FALSE) {
                return (USER_DONT_EXIST);
            } else {
                return (1);
            }
        } catch (PDOException $e) {
            throw new Exception("Error update_login in User Model:<br/>" . $e->getMessage());
        }
    }

    public function update_password($new_password, $login) {
        try {
            parent::db_connect();
            $sql = "UPDATE `users`
                    SET `password`=?
                    WHERE LOWER(`login`)=? AND `active`='1'";
            $this->stmt = $this->pdo->prepare($sql);
            $return_value = $this->stmt->execute(array($new_password, $login));
            if ($return_value) {
                $affected_rows = $this->stmt->rowCount();
                if ($affected_rows == 0) {
                    parent::db_drop_connection();
                    return (USER_DONT_EXIST);
                } else {
                    parent::db_drop_connection();
                    return (1);
                }
            }
        } catch (PDOException $e) {
            throw new Exception("Error update_email in User Model:<br/>" . $e->getMessage());
        }
    }

    public function update_email($new_email, $old_email, $current_user) {
        try {
            parent::db_connect();
            $sql = "UPDATE `users`
                    SET `email`=?
                    WHERE (LOWER(`email`)=? AND LOWER(`login`)=?)";
            $this->stmt = $this->pdo->prepare($sql);
            $return_value = $this->stmt->execute(array($new_email, $old_email, $current_user));
            if ($return_value) {
                $affected_rows = $this->stmt->rowCount();
                if ($affected_rows == 0) {
                    parent::db_drop_connection();
                    return (USER_DONT_EXIST);
                } else {
                    parent::db_drop_connection();
                    return (1);
                }
            }
        } catch (PDOException $e) {
            throw new Exception("Error update_login in User Model:<br/>" . $e->getMessage());
        }
    }

    public function update_notification_email($new_email, $old_email, $current_user) {
        try {
            parent::db_connect();
            $sql = "UPDATE `users`
                    SET `notification_email`=?
                    WHERE (LOWER(`notification_email`)=? AND LOWER(`login`)=?)";
            $this->stmt = $this->pdo->prepare($sql);
            $return_value = $this->stmt->execute(array($new_email, $old_email, $current_user));
            if ($return_value) {
                $affected_rows = $this->stmt->rowCount();
                if ($affected_rows == 0) {
                    parent::db_drop_connection();
                    return (USER_DONT_EXIST);
                } else {
                    parent::db_drop_connection();
                    return (1);
                }
            }
        } catch (PDOException $e) {
            throw new Exception("Error update_login in User Model:<br/>" . $e->getMessage());
        }
    }

    public function toggle_notification_active($user_id, $notification_active) {
        try {
            if ($notification_active == 1) {
                parent::db_connect();
                $sql = "UPDATE `users`
                        SET `notification_active`= 0
                        WHERE `user_id`=?";
                $this->stmt = $this->pdo->prepare($sql);
                $this->stmt->execute(array($user_id));
                parent::db_drop_connection();
                return (2);
            } else {
                parent::db_connect();
                $sql = "UPDATE `users`
                        SET `notification_active`= 1
                        WHERE `user_id`=?";
                $this->stmt = $this->pdo->prepare($sql);
                $this->stmt->execute(array($user_id));
                parent::db_drop_connection();
                return (1);
            }
        } catch (Exception $e) {
            throw new Exception("Error toggle_notification_active in User Model:" . $e->getMessage());
        }
    }
}