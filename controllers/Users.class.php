<?php
require_once("models/Model.class.php");
require_once("models/User.class.php");

class UsersController extends Controller {

    /*
    This function takes an array and verify if the keys exists inside.
    Check if the inputs to create an account has at least something in it
    */
    private static function verif_input_exists(array $arr) {

    }

    public function create_user(array $kwargs) {
        try {
            $keys = ["password", "password2", "login", "email"];
            if (!array_diff($keys, array_keys($kwargs)))
            {
                $_SESSION['last_email'] = (array_key_exists("email", $kwargs)) ? $kwargs["email"] : "";
                $_SESSION['last_login'] = (array_key_exists("login", $kwargs)) ? $kwargs["login"] : "";
                return "Some input are empty\n";
            }
            $user = new UserModel;
            if ($password == $password_verif) {
                if (self::email_valid($email) && self::login_valid($login)) {
                    
                } else {
                    $_SESSION['last_email'] = $email;
                    $_SESSION['last_login'] = $login;
                    UserController::createView("create_user_form");
                    return "Email or login are not well formatted\n";
                }
                $kwargs = [
                    "email" => $email,
                    "login" => $login,
                    "password" => hash("whirlpool", $password),
                    "profile_pic" => "assets/img_pic/"
                ];
                if ($user->create_user($kwargs)) {
                    return "success\n";
                } else {
                    return "This email is already used\n";
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
		return (0);		
    }
    /*
	This function take an user input and verify if the input is a well-formatted login.
	*/
	public static function login_valid($login) {
		return (0);
	}
}