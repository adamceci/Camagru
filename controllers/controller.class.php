<?php
class Controller {

    static $errors = [];

	/*
	This function takes a view name as parameter and will get the file if it exists.
	A view is a part of a website that is used only once in the website (Ex: The login view)
	*/
	public static function createView($viewName){
		require_once("views/header.module.php");
		require_once('./views/'.$viewName.'.view.php');
	    require_once("views/footer.module.php");
	}

	/* 
	This function takes a module name as parameter and will get the file if it exists.
	A module is a part of website that is used more than once and on different part of the website (Ex: The header)
	*/
	public static function createModule($moduleName){
		require_once('./views/'.$moduleName.'.module.php');
	}

	public static function template_index(){
        self::createModule("top_html_tags");
        self::createModule("header");
        self::createModule("index");
        self::createModule("footer");
        self::createModule("bottom_html_tags");
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

