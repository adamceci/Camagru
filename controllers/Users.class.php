<?php
require_once("models/Model.class.php");
require_once("models/User.class.php");

class UsersController extends Controller {

    public static function template_sign_up() {
        self::createModule("top_html_tags", 0);
        self::createModule("header", 0);
        self::createModule("create_user_form", 1);
        self::createModule("footer", 0);
        self::createModule("script", 0);
        self::createModule("bottom_html_tags", 0);
    }

    public static function template_login() {
        self::createModule("top_html_tags_grey", 0);
        self::createModule("header", 0);
        self::createModule("login", 1);
        self::createModule("footer", 0);
        self::createModule("script", 0);
        self::createModule("bottom_html_tags", 0);
    }

    public static function template_profile() {
        self::createModule("top_html_tags", 0);
        self::createModule("header", 0);
        self::createModule("profile", 1);
        if (array_key_exists("update", $_GET) && !empty($_GET['update'])) {
            if ($_GET['update'] == 'login' || $_GET['update'] == 'email' || $_GET['update'] == 'password' || $_GET['update'] == 'notification_email' || $_GET['update'] == 'profile_pic')
            UsersController::createModule('update_'. $_GET['update'], 0);
        }
        self::createModule("footer", 0);
        self::createModule("script", 0);
        self::createModule("bottom_html_tags", 0);
    }

    public static function template_password_recovery() {
        self::createModule("top_html_tags_grey", 0);
        self::createModule("header", 0);
        self::createModule('password_recovery', 1);
        self::createModule("script", 0);
        self::createModule("footer", 0);
        self::createModule("bottom_html_tags", 0);
    }

    public static function template_password_change() {
        self::createModule("top_html_tags", 0);
        self::createModule("header", 0);
        self::createModule('update_password_rec', 1);
        self::createModule("script", 0);
        self::createModule("footer", 0);
        self::createModule("bottom_html_tags", 0);
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
    private static function fill_session_error(array $arr, $error_msg) {
        $_SESSION['last_email'] = (array_key_exists("email", $arr)) ? $arr["email"] : "";
        $_SESSION['last_login'] = (array_key_exists("login", $arr)) ? $arr["login"] : "";
        self::$errors[] = $error_msg;
    }

    /*
    This function takes an array and verify if the keys exists inside.
    Check if the $_POST has the keys in it.
    */
    private static function info_creation_exists(array $keys, array $arr) {
        if (array_diff($keys, array_keys($arr))) {
            return (FALSE);
        }
        return (TRUE);
    }


    private static function fill_current_user_login($valid_field) {
        $user = new User;
        $content = $user->get_info($valid_field);
        if ($content != FALSE) {
            $_SESSION["current_user"] = $content[0]["login"];
            $_SESSION["current_user_email"] = $content[0]["email"];
            $_SESSION["current_user_pic"] = $content[0]["profile_pic"];
            $_SESSION["current_user_user_id"] = $content[0]["user_id"];
            $_SESSION["current_user_notification_email"] = $content[0]["notification_email"];
            $_SESSION["current_user_notification_active"] = $content[0]["notification_active"];
            $_SESSION["current_user_date_of_creation"] = $content[0]["date_of_creation"];
            return (1);
        }
        return (0);
    }

    private static function manage_file_errors($nb_error) {
        switch ($nb_error) {
            case 1:
                return ('The uploaded file exceeds the max size'); #from php.ini
            case 2:
                return ('The uploaded file exceeds the max size'); #from html form
            case 3:
                return ('The uploaded file was only partially uploaded.');
            case 4:
                return ('No file was uploaded');
            case 6:
                return ('Missing a temporary folder');
            case 7:
                return ('Failed to write file to disk.');
            case 8:
                return ('A PHP extension stopped the file upload.');
        }
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
        if (input_useable($_FILES, $fieldname)) {
            if ($_FILES[$fieldname]['error'] !== 0) {
                self::fill_session_error(array(), self::manage_file_errors($_FILES[$fieldname]['error']));
                return (0);
            }
            var_dump("test");
            if (!input_useable($_FILES[$fieldname], 'tmp_name')) {
                var_dump("test");
                self::fill_session_error(array(), 'Can\'t upload a file with an empty name');
                return (0);
            }
            var_dump("test");
            if (!is_uploaded_file($_FILES[$fieldname]['tmp_name'])) {
                self::fill_session_error(array(), 'not an HTTP upload');
                return (0);
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
            return (0);
        }
    }

    private static function creation_user_response($return_val, $arr) {
        switch ($return_val) {
            case 1:
                if (self::send_verification_email($arr)) {
                    $_SESSION['success'] = "Your account has been made,<br />
                                            please verify it by clicking the activation link 
                                            that has been sent to your email.<br />";
                    return (1);
                } else {
//                   $user = new User;
//                    try {
//                        $user->delete_user($arr['login'], $arr['password']);
//                    } catch (Exception $e) {
//                        self::fill_session_error(array(), "Error creation_user_response for deleting user" . $e->getMessage());
//                        return (0);
//                    }
//                    self::fill_session_error(array(), "The verification email wasn't sent");
                    return (0);
                }
            case EMAIL_EXISTS:
                self::fill_session_error($arr, "The email already exists");
                return (0);
            case LOGIN_EXISTS:
                self::fill_session_error($arr, "The login already exists");
                return (0);
            case EMAIL_EXISTS | LOGIN_EXISTS:
                self::fill_session_error($arr, "The login and email already exists");
                return (0);
        }
    }

    private static function activation_user_response($return_val, $email) {
        switch ($return_val) {
            case 1:
                self::fill_current_user_login($email);
                $_SESSION['success'] = 'The account has been activated. Welcome to the BDClub';
                return (1);
            case USER_DONT_EXIST:
                echo "The email or the hash doesn't exist";
                return (0);
        }
    }

    /*
    create_user(array $kwargs)
    */
    public static function create_user(array $kwargs) {
        try {
            $keys = ["password", "password_verif", "login", "email"];
            if ((self::info_creation_exists($keys, $kwargs)) == FALSE) {
                self::fill_session_error($kwargs, "Empty fields");
                return (0);
            }
            if ($kwargs["password"] == $kwargs["password_verif"]) {
                $kwargs_model = [
                    "email" => array_key_exists("email", $kwargs) ? $kwargs["email"] : "",
                    "login" => array_key_exists("login", $kwargs) ? $kwargs["login"] : "",
                    "password" => array_key_exists("password", $kwargs) ? hash("whirlpool", $kwargs["password"]) : "",
                    "profile_pic" => "default.png",
                    "verif_hash" => md5(rand(9101994, 11021994))
                ];
                $user = new User;
                if (self::email_valid($kwargs_model['email']) && self::login_valid($kwargs_model['login']) && self::password_valid($kwargs['password'])) {
                    $return_value = $user->create_user($kwargs_model);
                    return self::creation_user_response($return_value, $kwargs_model);
                } else {
                    self::fill_session_error($kwargs, "Email or login or password are not well formatted");
                    return (0);
                }
            } else {
                self::fill_session_error($kwargs, "Password verification and password are not the same");
                return (0);
            }
        }
        catch (Exception $e) {
            self::fill_session_error(array(), "FATAL ERROR ! " . $e->getMessage());
            return (0);
        }
    }

    /*
    send_verification_email($email, $hash) verify if the hash and the email sent by the user correspond to a verification email.
    Then, it'll send the user logged on the index.
    */
    private static function send_verification_email($user_info) {
        if (self::email_valid($user_info["email"]) && self::md5_valid($user_info["verif_hash"])) {
            $to = "a.ceciora@gmail.com";
            $from = "aceciora@student.s19.be";
            $headers = "MIME-Version: 1.0" . "\r\n"; 
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: '.$from. "\r\n"; 
            $subject = "Verification mail Camagru";
            $link = "http://localhost:8080/Camagru/index.php?email=" . $user_info['email'] . "&hash=" . $user_info["verif_hash"] . "&login=" . $user_info['login'];
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

    private static function send_recovery_email($login, $email, $hash) {
        $to = "gabriele_Virga@hotmail.com";
        //$to = $email;
        $from = "gvirga@student.s19.be";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: '.$from. "\r\n";
        $subject = "Verification mail Camagru";
        $link = "http://localhost:8080/Camagru/index.php?url=password_reset&email=" . $login . "&hash=" . $hash;
        $message = '<h1>Have you asked a new password ?</h1>
                    <p>Account: ' . $login . '</p>
                    <p>If it is the case, click the link below to get a new password</p>
            <a href="' . $link . '"> localhost:8080/Camagru/verif </a>';
        if (mail($to, $subject, $message, $headers)) {
            return (1);
        } else {
            return (0);
        }
    }

    public static function activate_account($email, $login, $hash) {
        if (self::email_valid($email) && self::md5_valid($hash)) {
            $user = new User;
            try {
                $return_value = $user->activate_account($email, $login, $hash);
            } catch (Exception $e) {
                self::fill_session_error(array(), "ERROR:" . $e->getMessage());
                return (0);
            }
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
        if (preg_match("/^(?=.{3,26}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/",$login)) {
            return (1);
        }
		return (0);
	}

    private static function password_valid($password) {
        if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{5,}\w+/", $password)) {
            return (1);
        }
        return (0);
    }

	private static function login_verif(array $kwargs) {
	    $user = new User;
        if (array_key_exists("login", $kwargs) && !empty($kwargs["login"])) {
            if ((self::login_valid($kwargs["login"]) || self::email_valid($kwargs["login"]))
            && $user->auth_user($kwargs["login"], hash("whirlpool", $kwargs["password"]))) {
                self::fill_current_user_login($kwargs["login"]);
                return (1);
            }
        }
        return (0);
    }

	public static function login(array $kwargs) {
        if (input_useable($kwargs, 'password')) {
            if (self::login_verif($kwargs)) {
                return (1);
            } else {
                self::fill_session_error($kwargs, "Wrong login or password");
                return (0);
            }
        }
        self::fill_session_error($kwargs, "Empty password");
        return (0);
    }

    public static function logout() {
        session_destroy();
        header("Location: index");
    }

    private static function update_password($user_info) {
        if (self::password_valid($user_info['new_password']) && self::password_valid($user_info['old_password'])) {
            try {
                $user = new User;
                if (!($user->auth_user($_SESSION['current_user'], hash('whirlpool', $user_info['old_password'])))) {
                    self::fill_session_error(array(), 'The passwords doesn\'t match');
                    $_SESSION['errors'] = self::get_errors();
                    header('Location: profile&update=password');
                    return (0);
                }
                if ($user->update_password(hash("whirlpool", $user_info['new_password']), $_SESSION['current_user']) != USER_DONT_EXIST) {
                    self::fill_current_user_login($_SESSION['current_user']);
                    $_SESSION['success'] = "Password changed successfully";
                    header('Location: profile');
                    return (1);
                }
            } catch (Exception $e) {
                self::fill_session_error(array(), $e->getMessage());
                $_SESSION['errors'] = self::get_errors();
                header('Location: profile&update=password');
                return (0);
            }
        } else {
            self::fill_session_error(array(), 'Password not well formatted');
            $_SESSION['errors'] = self::get_errors();
            header('Location: profile&update=password');
            return (0);
        }
    }

    private static function update_login($user_info) {
        $user = new User;
        if (self::login_valid($user_info['new_login'])) {
            try {
                $user_exist = $user->get_user_id($user_info['new_login']);
                if ($user_exist) {
                    self::fill_session_error(array(), 'Username already exists');
                    $_SESSION['errors'] = self::get_errors();
                    header('Location: profile&update=login');
                    return (0);
                }
                if ($user->update_login($user_info['new_login'], $_SESSION['current_user'])) {
                    self::fill_current_user_login($user_info['new_login']);
                    header("Location: profile");
                    return (1);
                } else {
                    self::fill_session_error(array(), 'Couldn\'t update username');
                    $_SESSION['errors'] = self::get_errors();
                    header('Location: profile&update=login');
                    return (0);
                }
            } catch (Exception $e) {
                self::fill_session_error(array(), $e->getMessage());
                $_SESSION['errors'] = self::get_errors();
                header('Location: profile&update=login');
                return (0);
            }
        } else {
            self::fill_session_error(array(), 'Login not well formatted');
            $_SESSION['errors'] = self::get_errors();
            header('Location: profile&update=login');
            return (0);
        }
    }

    private static function update_profile_pic() {
        $user = new User;
        try {
            $profile_pic = self::upload_profile_pic("new_profile_pic");
            if ($profile_pic !== 0) {
                if ($user->update_profile_pic($profile_pic, $_SESSION['current_user'])) {
                    self::fill_current_user_login($_SESSION['current_user']);
                    header('Location: profile');
                    return (1);
                } else {
                    self::fill_session_error(array(), "Couldn't update profile pic");
                    $_SESSION['errors'] = self::get_errors();
                    header('Location: profile&update=profile_pic');
                    return (0);
                }
            } else {
                $_SESSION['errors'] = self::get_errors();
                header('Location: profile&update=profile_pic');
                return (0);
            }
        } catch (Exception $e) {
            self::fill_session_error(array(), $e->getMessage());
            $_SESSION['errors'] = self::get_errors();
            header('Location: profile&update=profile_pic');
            return (0);
        }
    }

    private static function update_email($user_info) {
        if (self::email_valid($user_info['new_email'])) {
            try {
                $user = new User;
                $email_exist = $user->get_email($user_info['new_email']);
                if ($email_exist) {
                    self::fill_session_error(array('email' => $user_info['new_email']), 'Mail already exists');
                    $_SESSION['errors'] = self::get_errors();
                    header('Location: profile&update=email');
                    return (0);
                }
                if ($user->update_email($user_info['new_email'], $_SESSION["current_user_email"], $_SESSION['current_user']) != USER_DONT_EXIST) {
                    self::fill_current_user_login($user_info['new_email']);
                    header("Location: profile");
                    return (1);
                } else {
                    self::fill_session_error(array('email' => $user_info['new_email']), 'Error updating the email');
                    $_SESSION['errors'] = self::get_errors();
                    header('Location: profile&update=email');
                    return (0);
                }
            } catch (Exception $e) {
                self::fill_session_error(array('email' => $user_info['new_email']),$e->getMessage());
                $_SESSION['errors'] = self::get_errors();
                header('Location: profile&update=email');
                return (0);
            }
        } else {
            self::fill_session_error(array('email' => $user_info['new_email']), 'Wrong format for the email');
            $_SESSION['errors'] = self::get_errors();
            header('Location: profile&update=email');
            return (0);
        }
    }

    private static function update_notification_email($user_info) {
        if (self::email_valid($user_info['new_notification_email'])) {
            try {
                $user = new User;
                if ($user->update_notification_email($user_info['new_notification_email'], $_SESSION["current_user_notification_email"], $_SESSION['current_user']) != USER_DONT_EXIST) {
                    self::fill_current_user_login($_SESSION['current_user']);
                    header("Location: profile");
                    return (1);
                } else {
                    self::fill_session_error(array(), 'Couldn\'t update email');
                    $_SESSION['errors'] = self::get_errors();
                    header('Location: profile&update=notification_email');
                    return (0);
                }
            } catch (Exception $e) {
                self::fill_session_error(array(), $e->getMessage());
                $_SESSION['errors'] = self::get_errors();
                header('Location: profile&update=notification_email');
                return (0);
            }
        } else {
            self::fill_session_error(array(), 'Email not well formatted');
            $_SESSION['errors'] = self::get_errors();
            header('Location: profile&update=notification_email');
            return (0);
        }
    }

    public static function update_notification_active() {
        try {
            if (input_useable($_SESSION, 'current_user_user_id') && array_key_exists('current_user_notification_active', $_SESSION)) {
                $user = new User;
                $res = $user->toggle_notification_active($_SESSION['current_user_user_id'], $_SESSION['current_user_notification_active']);
                self::fill_current_user_login($_SESSION['current_user']);
                if ($res == 2)
                    $_SESSION['success'] = 'The notifications are turned off!<br/>You will no longer receive an email when someone comment on your posts';
                else if ($res == 1)
                    $_SESSION['success'] = 'The notifications are turned on!<br/>You will receive an email when someone comment on your posts';
                header('Location: profile');
                return (1);
            } else {
                self::fill_session_error(array(), 'Couldn\'t find user');
                $_SESSION['errors'] = self::get_errors();
                header('Location: profile');
                return (0);
            }
        } catch (Exception $e) {
            self::fill_session_error(array(), $e->getMessage());
            $_SESSION['errors'] = self::get_errors();
            header('Location: profile');
            return (0);
        }
    }

    public static function update_user($column) {
	    var_dump($column);
        if (array_key_exists("new_login", $column)) {
            self::update_login($column);
        } else if (array_key_exists("new_password", $column) && array_key_exists("old_password", $column)) {
            self::update_password($column);
        } else if (array_key_exists('new_email', $column)) {
            self::update_email($column);
        } else if (array_key_exists("new_notification_email", $column)) {
            self::update_notification_email($column);
        } else if (array_key_exists("new_profile_pic", $_FILES)) {
            self::update_profile_pic();
        } else if (array_key_exists('update_notification_active', $column))
            self::update_notification_active();
	}

	public static function password_recovery($info) {
	    if (input_useable($info, 'login')) {
            if (self::login_valid($info['login']) || self::email_valid($info['login'])) {
                $user = New User;
                if ($user->user_login_or_email_exist($info['login'], $info['login'])) {
                    $hash = md5(rand(9101994, 11021994));
                    $email = $user->get_email($info['login']);
                    if (!array_key_exists('email', $email)
                        || !($user->update_hash($info['login'], $hash))
                        || !(self::send_recovery_email($info['login'], $email['email'], $hash))) {
                        self::$errors[] = 'The email wasn\'t send';
                        return (0);
                    }
                    $_SESSION['success'] = "<p class='success'>Password recovery email sent,<br />
                                            check your email to see the activation link.</p>";
                    return (1);
                }
            }
            self::$errors[] = 'Wrong email or username';
            return (0);
        } else {
            self::$errors[] = 'Can\'t recover an empty input';
            return (0);
        }
    }

    public static function password_recovery_update() {
	    if (input_useable($_GET, 'login')
            && input_useable($_GET, 'hash')) {
	        if (!input_useable($_POST, 'password')
                || !input_useable($_POST, 'password_verif')) {
                self::$errors[] = 'Empty password can not be a password';
                return (0);
            } else if ($_POST['password'] !== $_POST['password_verif']) {
                self::$errors[] = 'The passwords are not the same';
                return (0);
            }
            if (self::password_valid($_POST['password'])
                && self::password_valid($_POST['password_verif'])) {
                try {
                    $user = New User;
                    if ($user->activate_account($_GET['login'], $_GET['login'], $_GET['hash']) === USER_DONT_EXIST) {
                        self::$errors[] = 'Couldn\'t change the password';
                        return (0);
                    } else {
                        $user->update_password(hash('whirlpool', $_POST['password']), $_GET['login']);
                        if ($user->auth_user($_GET['login'], hash('whirlpool',$_POST['password'])) == 1) {
                            self::fill_current_user_login($_GET['login']);
                            $hash = md5(rand(9101994, 11021994));
                            $user->update_hash($_GET['login'], $hash);
                            $_SESSION['success'] = 'Welcome back !';
                            return (1);
                        } else {
                            self::$errors[] = 'Couldn\'t login';
                            return (0);
                        }
                    }
                } catch (Exception $e) {
                    self::$errors[] = $e->getMessage();
                    return (0);
                }
            } else {
                self::$errors[] = 'Wrong format';
                return (0);
            }
        } else {
	        self::$errors[] = 'How did you get here?!';
	        return (0);
        }
    }
}