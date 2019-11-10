<?php
class Controller {

    static $errors = [];
    static $info = [];
	/*
	This function takes a view name as parameter and will get the file if it exists.
	A view is a part of a website that is used only once in the website (Ex: The login view)
	*/
	public static function createView($viewName){
        self::createModule("top_html_tags", 0);
        require_once("views/header.module.php");
		require_once('./views/'.$viewName.'.view.php');
	    require_once("views/footer.module.php");
        self::createModule("bottom_html_tags", 0);
	}

	/* 
	This function takes a module name as parameter and will get the file if it exists.
	A module is a part of website that is used more than once and on different part of the website (Ex: The header)
	*/
	public static function createModule($moduleName, $i){
        if ($i == 1) {
            $information = self::$info;
            self::$info = [];
        }
        require_once('./views/'.$moduleName.'.module.php');
	}

	public static function template_index(){
        self::createModule("top_html_tags", 0);
        self::createModule("header", 0);
        self::createModule("index", 1);
        self::createModule("footer", 0);
        self::createModule("script_likes", 0);
        self::createModule("bottom_html_tags", 0);
    }

    public static function get_errors() {
	    $all_errors = self::$errors;
	    self::$errors = [];
        return ($all_errors);
    }

    public static function show_success_msg() {
	    $return_str = $_SESSION['success'];
        $_SESSION['success'] = "";
        return $return_str;
    }
}

