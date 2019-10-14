<?php
require_once("models/Model.class.php");
require_once("models/Users.class.php");

class UsersController extends Controller {

    /*
    This function takes an array and fill the session 
    */

    private static function fill_session_error(array $arr, $url) {
        $_GET["url"] = $url;
        $_SESSION['last_email'] = (array_key_exists("email", $arr)) ? $arr["email"] : "";
        $_SESSION['last_login'] = (array_key_exists("login", $arr)) ? $arr["login"] : "";
    }

    /*
    This function takes an array and verify if the keys exists inside.
    Check if the $_POST has the keys in it.
    */
    private static function info_creation_exists(array $keys, array $arr) {
        if (!array_diff($keys, array_keys($arr))) {
            self::fill_session_error($arr, "sign_up");
            UsersController::createView("create_user_form");
            return (FALSE);
        }
        return (TRUE);
    }

    private static function creation_user_response($return_val, $arr) {
        switch ($return_val) {
            case 0:
                $_SESSION["logged_on_user"] = $arr["login"];
                return "Your account has been made, <br /> please verify it by clicking the activation link that has been send to your email.<br />";
            case 1:
                self::fill_session_error($arr, "sign_up");
                UsersController::createView("create_user_form");
                return "The email already exists\n";
            case 2:
                self::fill_session_error($arr, "sign_up");
                UsersController::createView("create_user_form");
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
                    "profile_pic" => array_key_exists("profile_pic", $kwargs) ? $kwargs["profile_pic"] : "",
                    "verif_hash" => md5(rand(9101994, 11021994))
                ];
                if (self::email_valid($kwargs_model['email']) && self::login_valid($kwargs_model['login'])) {
                    return self::creation_user_response($user->create_user($kwargs_model), $kwargs_model);
                } else {
                    self::fill_session_error($kwargs, "sign_up");
                    UsersController::createView("create_user_form");
                    return "Email or login are not well formatted\n";
                }
            } else {
                self::fill_session_error($kwargs, "sign_up");
                UsersController::createView("create_user_form");
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
        if (preg_match("/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD",$email)) {
            return (1);
        }
        return (0);
    }
    /*
	This function take an user input and verify if the input is a well-formatted login.
	*/
	public static function login_valid($login) {
        if (preg_match("/^(?=.{5,26}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/",$login)) {
            return (1);
        }
		return (0);
	}
}