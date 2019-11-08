<?php
class Controller {

    static $errors = [];
    static $info = [];
	/*
	This function takes a view name as parameter and will get the file if it exists.
	A view is a part of a website that is used only once in the website (Ex: The login view)
	*/
	public static function createView($viewName){
        self::createModule("top_html_tags");
		require_once("views/header.module.php");
		require_once('./views/'.$viewName.'.view.php');
	    require_once("views/footer.module.php");
        self::createModule("bottom_html_tags");
	}

	/* 
	This function takes a module name as parameter and will get the file if it exists.
	A module is a part of website that is used more than once and on different part of the website (Ex: The header)
	*/
	public static function createModule($moduleName){
	    $information = self::$info;
	    self::$info = "";
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

	//upload image
	public static function upload_image() {

		// Current working directory ("/Camagru-MVC-/")
		$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
		// Directory that will receive uploaded files
		$uploads_directory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'assets/tmp_pics/';
		// Location of index page
		$index_page = "http://" . $_SERVER["HTTP_HOST"] . $directory_self;
		// Location of the upload form
		$upload_form = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'montage';

		echo "directory_self = " . $directory_self . "\n";
		echo "uploads_directory = " . $uploads_directory . "\n";
		echo "index_page = " . $index_page . "\n";
		echo "upload_form = " . $upload_form . "\n";

		// location of the success page
		// $uploadSuccess = $upload_form;

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
		if (!(isset($kwargs) && (array_key_exists("submit_create_post", $kwargs) || array_key_exists("save", $kwargs)))) {
    		// check if user is logged in
    		if (isset($_SESSION["current_user"]))
        		self::error_post('the upload form is neaded', $upload_form);
    		else
				self::error_post('log in before accessing this page', $index_page);
			// return (0);
		}
		// check for PHP's built-in uploading errors
		if ($_FILES[$fieldname]['error'] !== 0) {
			self::error_post($errors[$_FILES[$fieldname]['error']], $upload_form);
		}
		// check that the file we are working on really was the subject of an HTTP upload
		if (!is_uploaded_file($_FILES[$fieldname]['tmp_name']))
			self::error_post('not an HTTP upload', $upload_form);
		// validation... since this is an image upload script we should run a check
		// to make sure the uploaded file is in fact an image. Here is a simple check:
		// getimagesize() returns false if the file tested is not an image.
		if (!(getimagesize($_FILES[$fieldname]['tmp_name'])))
			self::error_post('only image uploads are allowed', $upload_form);
		// make a unique filename for the uploaded file and check it is not already
		// taken... if it is already taken keep trying until we find a vacant one
		// sample filename: 1140732936-filename.jpg
		if (!file_exists($uploads_directory))
			mkdir($uploads_directory, 0755, true);
		$now = time();
		while(file_exists($uploadFilename = $uploads_directory.$now.'-'.$_FILES[$fieldname]['name']))
			$now++;
		// now let's move the file to its final location and allocate the new filename to it
		if (!(move_uploaded_file($_FILES[$fieldname]['tmp_name'], $uploadFilename)))
			error('receiving directory insuffiecient permission', $upload_form);
		$ret = basename($uploadFilename);
		return ($ret);
		// This far, everything has worked and the file has been successfully saved.
		// We are now going to redirect the client to a success page.
		// header('Location: ' . $uploadSuccess);

	}
}

