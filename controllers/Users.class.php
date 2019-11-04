<?php
require_once("models/Model.class.php");
require_once("models/User.class.php");

class UsersController extends Controller {

    public static function template_sign_up() {
        self::createView("create_user_form");
    }

    public static function template_login() {
        self::createView("login");
    }

    public static function template_profile() {
        self::createModule("header");
        self::createView("profile");
        if (array_key_exists("update", $_GET) && !empty($_GET['update'])) {
            if ($_GET['update'] == 'login' || $_GET['update'] == 'email' || $_GET['update'] == 'password' || $_GET['update'] == 'notification_email' || $_GET['update'] == 'profile_pic')
            UsersController::createModule('update_' . $_GET['update']);
        }
        self::createModule("footer");
    }

    private static function show_errors() {
        $errors = self::get_errors();
        echo "<div class=\"errors_box\">";
        foreach ($errors as $error) {
            echo "<p class=\"error\">". $error . "</p>";
        }
        echo "</div>";
    }
    /*
    This function takes an array and fill the session 
    */
    private static function fill_session_error(array $arr, $url) {
        $_SESSION['last_email'] = (array_key_exists("email", $arr)) ? $arr["email"] : "";
        $_SESSION['last_login'] = (array_key_exists("login", $arr)) ? $arr["login"] : "";
//        header("Location: $url");
    }

    /*
    This function takes an array and verify if the keys exists inside.
    Check if the $_POST has the keys in it.
    */
    private static function info_creation_exists(array $keys, array $arr) {
        if (!array_diff($keys, array_keys($arr))) {
            return (FALSE);
        }
        return (TRUE);
    }


    private static function fill_current_user_login($valid_field, $url) {
        $user = new User;
        $content = $user->get_info($valid_field);
        if ($content != FALSE) {
            $_SESSION["current_user"] = $content[0]["login"];
            $_SESSION["current_user_email"] = $content[0]["email"];
            $_SESSION["current_user_pic"] = $content[0]["profile_pic"];
            $_SESSION["current_user_user_id"] = $content[0]["user_id"];
            $_SESSION["current_user_notification_email"] = $content[0]["notification_email"];
            $_SESSION["current_user_date_of_creation"] = $content[0]["date_of_creation"];
            // Change to createTemplate
//            header("Location: $url");
            return (1);
        }
        return (0);
    }

    private function upload_profile_pic($key) {

        // Current working directory ("/Camagru-MVC-/")
        $directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
        // Directory that will receive uploaded files
        $uploads_directory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'assets/profile_pics/';
        // Location of index page
        $index_page = "http://" . $_SERVER["HTTP_HOST"] . $directory_self;
        // Location of the upload form
        $upload_form = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'sign_up';

        // location of the success page
        // $uploadSuccess = $upload_form;

        // fieldname used within the file <input> of the HTML form
        $fieldname = $key;
        // possible PHP upload errors
        $errors = array(
            1 => 'php.ini max file size exceeded',
            2 => 'html form max file size exceeded',
            3 => 'file upload was only partial',
            4 => 'no file was attached'
        );
        if (isset($_FILES) && array_key_exists($fieldname, $_FILES)) {
            if ($_FILES[$fieldname]['error'] !== 0) {
                self::$errors[] = $_FILES[$fieldname]['error'];
                self::fill_session_error(array(), 'sign_up');
            }
            if (!is_uploaded_file($_FILES[$fieldname]['tmp_name'])) {
                self::$errors[] = 'not an HTTP upload';
                self::fill_session_error(array(), 'sign_up');
            }
            // validation... since this is an image upload script we should run a check
            // to make sure the uploaded file is in fact an image. Here is a simple check:
            // getimagesize() returns false if the file tested is not an image.
            if (!(getimagesize($_FILES[$fieldname]['tmp_name']))) {
                self::$errors[] = 'Only image uploads are allowed';
                self::fill_session_error(array(), 'sign_up');
            }
            // make a unique filename for the uploaded file and check it is not already
            // taken... if it is already taken keep trying until we find a vacant one
            // sample filename: 1140732936-filename.jpg
            if (!file_exists($uploads_directory))
                mkdir($uploads_directory, 0755, true);
            $now = time();
            while (file_exists($uploadFilename = $uploads_directory . $now . '-' . $_FILES[$fieldname]['name']))
                $now++;
            // now let's move the file to its final location and allocate the new filename to it
            if (!(move_uploaded_file($_FILES[$fieldname]['tmp_name'], $uploadFilename))) {
                self::$errors[] = 'Receiving directory insufficient permission';
                self::fill_session_error(array(), 'sign_up');
            }
            $ret = basename($uploadFilename);
            return ($ret);
        } else {
            return ("");
        }
    }

    private static function creation_user_response($return_val, $arr) {
        switch ($return_val) {
            case 1:
                if (self::send_verification_email($arr)) {
                    return "<p>Your account has been made," ."<br />" ."please verify it by clicking the activation link that has been sent to your email.<br /></p>";
                } else {
                   $user = new User;
                    try {
                        $user->delete_user($arr['login'], $arr['password']);
                    } catch (Exception $e) {
                        echo "Error creation_user_response for deleting user" . $e->getMessage();
                    }
                    return "The verification email wasn't sent\n";
                }
            case EMAIL_EXISTS:
                self::fill_session_error($arr, "sign_up");
                return "The email already exists\n";
            case LOGIN_EXISTS:
                self::fill_session_error($arr, "sign_up");
                return "The login already exists\n";
            case EMAIL_EXISTS | LOGIN_EXISTS:
                self::fill_session_error($arr, "sign_up");
                return "The login and email already exists\n";
        }
    }

    private static function activation_user_response($return_val, $email) {
        switch ($return_val) {
            case 1:
                self::fill_current_user_login($email, "index");
                return "The account has been activated. Welcome to the BDClub";
            case USER_DONT_EXIST:
                $_GET["url"] = "index";
                // Change to createTemplate
                self::template_index();
                return "The email or the hash doesn't exist\n";
        }
    }

    /*
    create_user(array $kwargs)
    */
    public static function create_user(array $kwargs) {
        try {
            $keys = ["password", "password2", "login", "email", "profile_pic"];
            if ((self::info_creation_exists($keys, $kwargs)) == FALSE) {
                self::fill_session_error($kwargs, "sign_up");
                return "Error: empty inputs when creating an user\n";
            }
            if ($kwargs["password"] == $kwargs["password_verif"]) {
                $profile_pic = self::upload_profile_pic("profile_pic");
                $kwargs_model = [
                    "email" => array_key_exists("email", $kwargs) ? $kwargs["email"] : "",
                    "login" => array_key_exists("login", $kwargs) ? $kwargs["login"] : "",
                    "password" => array_key_exists("password", $kwargs) ? hash("whirlpool", $kwargs["password"]) : "",
                    "profile_pic" => empty($profile_pic) ? "assets/profile_pics/default.png" : $profile_pic,
                    "verif_hash" => md5(rand(9101994, 11021994))
                ];
                $user = new User;
                if (self::email_valid($kwargs_model['email']) && self::login_valid($kwargs_model['login'])) {
                    $return_value = $user->create_user($kwargs_model);
                    return self::creation_user_response($return_value, $kwargs_model);
                } else {
                    self::fill_session_error($kwargs, "sign_up");
                    echo "Email or login are not well formatted\n";
                }
            } else {
                self::fill_session_error($kwargs, "sign_up");
                return "Password verification and password are not the same";
            }
        }
        catch (Exception $e) {
            echo "FATAL ERROR ! " . $e->getMessage();
        }
    }

    /*
    send_verification_email($email, $hash) verify if the hash and the email sent by the user correspond to a verification email.
    Then, it'll send the user logged on the index.
    */
    private static function send_verification_email($user_info) {
        if (self::email_valid($user_info["email"]) && self::md5_valid($user_info["verif_hash"])) {
            $to = "adam@philou.com";
            $from = "gvirga@student.s19.be";
            $headers = "MIME-Version: 1.0" . "\r\n"; 
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: '.$from. "\r\n"; 
            $subject = "Verification mail Camagru";
            $link = "http://localhost:8080/Camagru/index.php?email=" . $user_info['email'] . "&hash=" . $user_info["verif_hash"] . "";
            $message = '<h1>Welcome in Camagru</h1>
                        <p>Account: ' . $user_info['login'] . '</p>
                        <p>Email: ' . $user_info['email'] . '</p>
                <a href="' . $link . '"> localhost:8080/Camagru/verif </a>';
            if (mail($to, $subject, $message, $headers)) {
                return (1);
            } else {
                return (0);
            }
        }
    }

    public static function activate_account($email, $hash) {
        if (self::email_valid($email) && self::md5_valid($hash)) {
            $user = new User;
            $return_value = $user->activate_account($email, $hash);
            return self::activation_user_response($return_value, $email);
        }
    } 
    

	/*
	email_valid($email) take an user input and verify if the input is a well-formatted email.
	*/
	private static function email_valid($email) {
        if (preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD',$email)) {
            return (1);
        }
        return (0);
    }
    /*
    md5_valid($md5_hash) takes an hash and verify if it has only the char authorized for md5. 
    */
    private static function md5_valid($hash) {
        if (preg_match('/^[0-9A-F]+$/iD', $hash)) {
            return (1);
        }
        return (0);
    }
    /*
    login_valid($login) take an user input and verify if the input is a well-formatted login.
	*/
	private static function login_valid($login) {
        if (preg_match("/^(?=.{5,26}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/",$login)) {
            return (1);
        }
		return (0);
	}

    private static function password_valid($password) {
        if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{5,}$/", $password)) {
            return (1);
        }
        return (0);
    }

	private static function login_verif(array $kwargs) {
	    $user = new User;
        if (array_key_exists("login", $kwargs) && !empty($kwargs["login"])) {
            if ((self::login_valid($kwargs["login"]) || self::email_valid($kwargs["login"]))
            && $user->auth_user($kwargs["login"], hash("whirlpool", $kwargs["password"]))) {
                return (self::fill_current_user_login($kwargs["login"], "index"));
            }
        }
        return (0);
    }

	public static function login(array $kwargs) {
        if (array_key_exists("password", $kwargs) && !empty($kwargs["password"])) {
            if (self::login_verif($kwargs)) {
                return ("Logged");
            } else {
                self::fill_session_error($kwargs, "login");
                return ("Wrong login or password\n");
            }
        }
        self::fill_session_error($kwargs, "login");
        return ("Empty password");
    }

    public static function logout() {
        session_destroy();
        $_GET["url"] = "index";
        // Change to createTemplate
        header("Location: index");
    }

    private static function update_password($user_info) {
        $user = new User;
        // Change to password_valid
        if (self::password_valid($user_info['new_password']) && self::password_valid($user_info['old_password'])) {
            try {
                if ($user->update_password(hash("whirlpool", $user_info['new_password']), hash("whirlpool", $user_info['old_password']), $_SESSION['current_user']) != USER_DONT_EXIST) {
                    echo "password changed";
                    return (1);
                } else {
                    echo "Wrong password";
                    $_SESSION['refresh'] = "profile&update=password";
                    self::fill_session_error(array(), 'profile');
                }
            } catch (Exception $e) {
                echo "FATAL ERROR:" . $e->getMessage();
            }
        } else {
            echo "Password not well formatted";
        }
    }

    private static function update_login($user_info) {
        $user = new User;
        if (self::login_valid($user_info['new_login'])) {
            try {
                if ($user->update_login($user_info['new_login'], $_SESSION['current_user'])) {
                    self::fill_current_user_login($user_info['new_login'], "profile");
                } else {
                    $_SESSION['refresh'] = "profile&update=login";
                    self::fill_session_error(array('login' => $user_info['new_login']), 'profile');
                }
            } catch (Exception $e) {
                echo "FATAL ERROR:" . $e->getMessage();
            }
        } else {
            echo "Login not well formatted";
            self::fill_session_error(array('login' => $user_info['new_login']), "profile");
        }
    }

    private static function update_profile_pic() {
        $user = new User;
        $profile_pic = self::upload_profile_pic("new_profile_pic");
        if (isset($_FILES) && array_key_exists("new_profile_pic", $_FILES)) {
            try {
                if ($user->update_profile_pic($profile_pic, $_SESSION['current_user'])) {
                    self::fill_current_user_login($_SESSION['current_user'], "profile");
                } else {
                    $_SESSION['refresh'] = 'profile&update=profile_pic';
                    self::fill_session_error(array(), 'profile');
                }
            } catch (Exception $e) {
                echo "FATAL ERROR:" . $e->getMessage();
            }
        } else {
            echo "Image not inserted";
            self::fill_session_error(array(), "profile");
        }
    }

    private static function update_email($user_info) {
        $user = new User;
        if (self::email_valid($user_info['new_email'])) {
            try {
                if ($user->update_email($user_info['new_email'], $_SESSION["current_user_email"], $_SESSION['current_user']) != USER_DONT_EXIST) {
                    self::fill_current_user_login($user_info['new_email'], "profile");
                } else {
                    $_SESSION['refresh'] = "profile&update=email";
                    self::fill_session_error(array('email' => $user_info['new_email']), 'profile');
                }
            } catch (Exception $e) {
                echo "FATAL ERROR:" . $e->getMessage();
            }
        } else {
            echo "Email not well formatted";
            self::fill_session_error(array('email' => $user_info['new_email']), "profile");
        }
    }

    private static function update_notification_email($user_info) {
        $user = new User;
        if (self::email_valid($user_info['new_notification_email'])) {
            try {
                if ($user->update_notification_email($user_info['new_notification_email'], $_SESSION["current_user_notification_email"], $_SESSION['current_user']) != USER_DONT_EXIST) {
                    self::fill_current_user_login($_SESSION['current_user'], "profile");
                } else {
                    self::fill_session_error(array('email' => $user_info['new_notification_email']), 'profile&update=notification_email');
                }
            } catch (Exception $e) {
                echo "FATAL ERROR:" . $e->getMessage();
            }
        } else {
            echo "Email not well formatted";
            self::fill_session_error(array('email' => $user_info['new_notification_email']), "profile");
        }
    }

    public static function update_user($column) {
        if (array_key_exists("new_login", $column)) {
            self::update_login($column);
        } else if (array_key_exists("new_password", $column) && array_key_exists("old_password", $column)) {
            self::update_password($column);
        } else if (array_key_exists("new_email", $column)) {
            self::update_email($column);
        } else if (array_key_exists("new_notification_email", $column)) {
            self::update_notification_email($column);
        } else if (array_key_exists("new_profile_pic", $_FILES)) {
            self::update_profile_pic();
        }
	}
}