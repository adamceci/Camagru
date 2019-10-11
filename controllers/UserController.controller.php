<?php
require_once("models/Model.class.php");
require_once("models/UserModel.model.php");

class UserController extends Controller {
    public function create_user($login, $email, $password) {
        try {
            $user = new UserModel;
            $kwargs = [
                "email" => $email,
                "login" => $login,
                "password" => hash("whirlpool", $password),
                "profile_pic" => "assets/img_pic/"
            ];    
            if ($user->create_user($kwargs)) {
                echo "success\n";
            }
        }
        catch (Exception $e) {
            echo "FATAL ERROR ! " . $e->getMessage();
        }
    }
}