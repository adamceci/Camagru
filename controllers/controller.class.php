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
            self::$info = array();
        }
        require_once('./views/'.$moduleName.'.module.php');
	}

	public static function template_index(){
        self::createModule("top_html_tags", 0);
        self::createModule("header", 0);
        self::createModule("index", 1);
        self::createModule("footer", 0);
        self::createModule("script_likes", 0);
        self::createModule("script_pagination", 0);
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

	protected static function generate_unique_name($dir, $file_name) {
		$now = time();
		while(file_exists($upload_file_name = $dir.$now.'-'.$file_name))
			$now++;
		return($upload_file_name);
	}

	//upload image
	private static function upload_image($dir_name) {

		// Current working directory ("/Camagru-MVC-/")
		$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
		// Directory that will receive uploaded files
		$uploads_directory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'assets/' . $dir_name;

		// fieldname used within the file <input> of the HTML form
		$fieldname = "image";
		// possible PHP upload errors
		$errors = array(
			1 => 'php.ini max file size exceeded',
            2 => 'html form max file size exceeded',
            3 => 'file upload was only partial',
			4 => 'no file was attached'
		);
		// check the upload form was actually submitted else print the form 
		if (!(isset($_POST) && (array_key_exists("upload_image", $_POST)))) {
    		// check if user is logged in
    		if (isset($_SESSION["current_user"])) {
				self::$errors[] = 'the upload form is neaded';
				return (0);
			}
    		else {
				self::$errors[] = 'log in before accessing this page';
				return (0);
			}
		}
		// check for PHP's built-in uploading errors
		if ($_FILES[$fieldname]['error'] !== 0) {
			self::$errors[] = $errors[$_FILES[$fieldname]['error']];
			return (0);
		}
		// check that the file we are working on really was the subject of an HTTP upload
		if (!is_uploaded_file($_FILES[$fieldname]['tmp_name'])) {
			self::$errors[] = 'not an HTTP upload';
			return (0);
		}
		// validation... since this is an image upload script we should run a check
		// to make sure the uploaded file is in fact an image. Here is a simple check:
		// getimagesize() returns false if the file tested is not an image.
		if (!(getimagesize($_FILES[$fieldname]['tmp_name']))) {
			self::$errors[] = 'only image uploads are allowed';
			return (0);
		}
		// make a unique filename for the uploaded file and check it is not already
		// taken... if it is already taken keep trying until we find a vacant one
		// sample filename: 1140732936-filename.jpg
		if (!file_exists($uploads_directory))
			mkdir($uploads_directory, 0755, true);
		$upload_file_name = self::generate_unique_name($uploads_directory, $_FILES[$fieldname]['name']);
		// now let's move the file to its final location and allocate the new filename to it
		if (!(move_uploaded_file($_FILES[$fieldname]['tmp_name'], $upload_file_name))) {
			self::$errors[] = 'receiving directory insuffiecient permission';
			return (0);
		}
		$file_name = basename($upload_file_name);
		return ($file_name);
	}

	public static function upload_file($dir_name) {
		$_SESSION["tmp_file_name"] = self::upload_image($dir_name);
		if ($_SESSION["tmp_file_name"] == 0)
			return (0);
		return (1);
	}
}