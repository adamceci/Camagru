<?php

require_once("models/Model.class.php");
require_once("models/PostsModel.class.php");
// session_start();

class PostsController extends Controller {

	private static $limit = 6;
	private static $offset;

	// display posts in index
	public static function display_index_posts($page) {
		try {
			if (!isset($page))
				$page = 1;
			$post = new Post;
			self::$offset = 6 * ($page - 1);
			$_SESSION["index_posts"] = $post->get_posts(self::$limit, self::$offset);
			parent::createView("index");
		}
		catch (Exception $e) {
			throw new Exception("Error while getting the index posts in PostsController " . $e->getMessage());
		}
	}

	// display posts in montage page
	public static function display_user_posts() {
		try {
			if (isset($_SESSION["current_user"])) {
				$post = new Post;
				$_SESSION["user_posts"] = $post->get_posts($post->number_user_posts(), 0);
				parent::CreateView("montage");
			}
		}
		catch (Exception $e) {
			throw new Exception("Error while getting the user posts in PostsController") . $e->getMessage();
		}
	}

	// error handling for post creation
	private function error_post($error, $location, $seconds = 5) {
		header("Refresh: $seconds; URL=$location");
		echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"'.
		'"http://www.w3.org/TR/html4/strict.dtd">'.
		'<html lang="en">'.
		'    <head>'.
		'        <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">'.
		'        <link rel="stylesheet" type="text/css" href="stylesheet.css">'.
		'    <title>Upload error</title>'.
		'    </head>'.
		'    <body>'.
		'    <div id="Upload">'.
		'        <h1>Upload failure</h1>'.
		'        <p>An error has occurred: '.
		'        <span class="red">' . $error . '...</span>'.
		'         The upload form is reloading</p>'.
		'     </div>'.
		'</html>';
		exit;
	}

	// upload post into /assets/post_imgs
	private function upload_post($kwargs) {

		// Current working directory ("/Camagru-MVC-/")
		$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
		// Directory that will receive uploaded files
		$uploads_directory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'assets/post_imgs/';
		// Location of index page
		$index_page = "http://" . $_SERVER["HTTP_HOST"] . $directory_self;
		// Location of the upload form
		$upload_form = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'montage';
		
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
		if (!(isset($kwargs) && array_key_exists("submit_create_post", $kwargs))) {
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
		$now = time();
		while(file_exists($uploadFilename = $uploads_directory.$now.'-'.$_FILES[$fieldname]['name']))
			$now++;
		// now let's move the file to its final location and allocate the new filename to it
		if (!(move_uploaded_file($_FILES[$fieldname]['tmp_name'], $uploadFilename)))
			error('receiving directory insuffiecient permission', $upload_form);
		// var_dump($uploadFilename);
		$ret = basename($uploadFilename);
		return ($ret);
		// This far, everything has worked and the file has been successfully saved.
		// We are now going to redirect the client to a success page.
		// header('Location: ' . $uploadSuccess);
	}

    // create post
    public static function create_post($kwargs) {
		try {
			if (isset($_SESSION["current_user"])) {
				$kwargs["image"] = self::upload_post($kwargs);
				if (isset($kwargs["image"])) {
					$post = new Post;
					$kwargs["user_id"] = (int)$_SESSION["current_user_user_id"];
					$post->create_post($kwargs);
				}
				else
					self::error_post("Please select an image", "montage");
				// if filter doesn't exist ? -> todo
        		// require_once(post_view.php);
			}
			else
				self::error_post("Log in before posting", "montage");
		}
		catch (Exception $e) {
            throw new Exception("Error while creating the post in controller " . $e->getMessage());
        }
	}
	
    // delete post
// 	private static function delete_post($kwargs) {
// 		try {
// 			if (isset($_SESSION["current_user"])) {
// 				if (isset($kwargs[""])) {

// 				}
// 			}
// 		}
// 		catch (Exception $e) {
// 			throw new Exception("Error while deleting the post in controller " . $e->getMessage());
// 		}
// 	}
}