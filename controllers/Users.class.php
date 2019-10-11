<?php
require_once("models/Model.class.php");
require_once("models/Users.class.php");

class UsersController extends Controller {

    /*
    This function takes an array and fill the session 
    */

    private static function fill_session_error(array $arr) {
        $_SESSION['last_email'] = (array_key_exists("email", $arr)) ? $arr["email"] : "";
        $_SESSION['last_login'] = (array_key_exists("login", $arr)) ? $arr["login"] : "";
    }

    /*
    This function takes an array and verify if the keys exists inside.
    Check if the $_POST has the keys in it.
    */
    private static function info_creation_exists(array $keys, array $arr) {
        if (!array_diff($keys, array_keys($arr))) {
            self::fill_session_error($arr);
            $_GET["url"] = "sign_up";
            UsersController::createView("create_user_form");
            return (FALSE);
        }
        return (TRUE);
    }

    private static function creation_user_response($return_val, $arr) {
        switch ($return_val) {
            case 0:
                return "Success\n";
            case 1:
                self::fill_session_error($arr);
                return "The email already exists\n";
            case 2:
                self::fill_session_error($arr);
                return "The login already exists\n";
        }
    }

    public function create_user(array $kwargs) {
        try {
            $keys = ["password", "password2", "login", "email", "profile_pic"];
            if ((self::info_creation_exists($keys, $kwargs)) == FALSE) {
                return "Error: empty inputs when creating an user\n";
            }
            $user = new UsersModel;
            if ($kwargs["password"] == $kwargs["password_verif"]) {
                $kwargs_model = [
                    "email" => array_key_exists("email", $kwargs) ? $kwargs["email"] : "",
                    "login" => array_key_exists("login", $kwargs) ? $kwargs["login"] : "",
                    "password" => array_key_exists("password", $kwargs) ? hash("whirlpool", $kwargs["password"]) : "",
                    "profile_pic" => array_key_exists("profile_pic", $kwargs) ? $kwargs["profile_pic"] : ""
                ];
                if (self::email_valid($kwargs_model['email']) && self::login_valid($kwargs_model['login'])) {
                    $_GET["url"] = "sign_up";
                    UsersController::createView("create_user_form");
                    return self::creation_user_response($user->create_user($kwargs_model), $kwargs_model);
                } else {
                    self::fill_session_error($kwargs);
                    $_GET["url"] = "sign_up";
                    UsersController::createView("create_user_form");
                    return "Email or login are not well formatted\n";
                }
            } else {
                return "Password verification and password are not the same";
            }
        }
        catch (Exception $e) {
            echo "FATAL ERROR ! " . $e->getMessage();
        }
    }

	/*
	This function take an user input and verify if the input is a well-formatted email.
	*/
	public static function email_valid($email) {
        if (preg_match("/^[A-Z_.0-9]+@(?:[A-Z0-9]+.)[A-Z0-9]+.[A-Z]{2,6}$/imu",$email)) {
            return (1);
        }
        return (0);
    }
    /*
	This function take an user input and verify if the input is a well-formatted login.
	*/
	public static function login_valid($login) {
        if (preg_match("/^[A-Za-z0-9_]+$/imu",$login)) {
            return (1);
        }
		return (0);
	}
}