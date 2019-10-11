<?php
require_once("models/Model.class.php");
require_once("models/UserModel.model.php");

class UserController extends Controller {
    public function create_user($login, $email, $password, $password_verif) {
        try {
            $user = new UserModel;
            if ($password == $password_verif) {
                if (self::email_valid($email) && self::login_valid($login)) {

                } else {
    
                }
                $kwargs = [
                    "email" => $email,
                    "login" => $login,
                    "password" => hash("whirlpool", $password),
                    "profile_pic" => "assets/img_pic/"
                ];
                if ($user->create_user($kwargs)) {
                    echo "success\n";
                } else {
                    echo "This email is already used\n";
                }
            } else {
                echo "Password verification and password are not the same";
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
		
	}
}