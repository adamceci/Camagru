<?php

require_once("models/Model.class.php");
require_once("models/Comment.class.php");
require_once("models/Like.class.php");
require_once("models/PostsModel.class.php");
date_default_timezone_set("Europe/Brussels");


class PostsController extends Controller implements Comments, Likes {

	private static $offset;

	public static function template_file_filters() {
		self::createModule("top_html_tags", 0);
		self::createModule("header", 0);
		self::createModule("montage_title", 0);
		self::createModule("open_container_tags", 0);
		self::createModule("montage_file", 0);
        self::createModule("montage_side", 1);
		self::createModule("close_container_tags", 0);
        self::createModule("footer", 0);
        self::createModule("bottom_html_tags", 0);
	}

	public static function upload($dir_name) {
		try {
			if (input_useable($_POST, "upload_image")) {
				parent::upload_file($dir_name);
				self::$info = self::get_user_images();
				self::template_file_filters();
			}
			else
				echo "upload_image vide"; // in self errors
		}
		catch (Exception $e) {
			throw new Exception("Error while uploading in PostsController " . $e->getMessage());
		}

	}

	public static function template_montage() {
        self::createModule("top_html_tags", 0);
		self::createModule("header", 0);
		self::createModule("montage_title", 0);
		self::createModule("open_container_tags", 0);
        self::createModule("montage_main", 0);
        self::createModule("montage_side", 1);
		self::createModule("close_container_tags", 0);
        self::createModule("footer", 0);
        self::createModule("bottom_html_tags", 0);
    }

	private static function get_index_posts($page) {
		$post = new Post;
		$_SESSION["nb_pages"] = (int)$post->get_nb_pages();
		self::$offset = 6 * ($page - 1);
		$index_posts = $post->get_index_posts(self::$offset);
		return ($index_posts);
	} 

	// get index posts from db and calls index template
	public static function display_index_posts($page) {
		try {
			if (!isset($page))
				$page = 1;
			self::$info = self::get_index_posts($page);
			$comments = new Comment;
			$likes = new Like;
			if (self::$info) {
			    foreach (self::$info as $post) {
			        self::$info[] = $comments->get_nbr_comments($post['post_id']);
			        self::$info[] = $likes->get_post_nblikes($post['post_id']);
			        if (input_useable($_SESSION, 'current_user')) {
			            $user = new User;
			            $user_id = $user->get_user_id($_SESSION['current_user']);
                        self::$info[][]['user_likes_it'] = $likes->is_active($user_id['user_id'], $post['post_id']);
                    } else {
                        self::$info[][]['user_likes_it'] = 0;
                    }
                }
            }
			parent::template_index();
		}
		catch (Exception $e) {
			throw new Exception("Error while getting the index posts in PostsController " . $e->getMessage());
		}
	}

	private static function get_user_images() {
		$post = new Post;
		$current_user_images = $post->get_user_images();
		return ($current_user_images);
	}

	// get current_user's images from db and calls montage template
	public static function display_user_posts() {
		try {
			if (isset($_SESSION["current_user"])) {
				self::$info = self::get_user_images();
				self::template_montage();
			}
		}
		catch (Exception $e) {
			throw new Exception("Error while getting the user posts in PostsController" . $e->getMessage());
		}
	}

	public static function template_comment() {
        self::createModule("top_html_tags", 0);
        self::createModule("header", 0);
        self::createModule("post_description", 0);
        self::$info = self::get_comments();
        self::createModule("comment", 1);
        self::createModule("footer", 0);
        self::createModule("script_comments", 0);
        self::createModule("bottom_html_tags", 0);
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

	// upload post into /assets/post_pics
	private function upload_post(array $kwargs) {

		// Current working directory ("/Camagru-MVC-/")
		$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
		// Directory that will receive uploaded files
		$uploads_directory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'assets/post_pics/';
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

    // create post
    public static function create_post(array $kwargs) {
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
				if (isset($kwargs["submit_create_post"])) {
					$kwargs["toPubSrc"] = $kwargs["image"];
					$kwargs["posted_time"] = date("Y-m-d H:i:s");
					$post->publish_post($kwargs);
				}
			}
			else
				self::error_post("Log in before posting", "montage");
		}
		catch (Exception $e) {
            throw new Exception("Error while creating the post in controller " . $e->getMessage());
        }
	}

	public static function publish_post(array $kwargs) {
		try {
			if (isset($_SESSION["current_user"])) {
				if (isset($kwargs["toPubSrc"])) {
					$kwargs["posted_time"] = date("Y-m-d H:i:s");
					$post = new Post;
					$post->publish_post($kwargs);
				}
				else
					echo "Missing source of the image to be published";
			}
			else
				echo "Need to connect before publishing a post";
		}
		catch (Exception $e) {
			throw new Exception("Error while deleting the post in controller " . $e->getMessage());
		}
	}

    // delete post
	public static function delete_post(array $kwargs) {
		try {
			if (isset($_SESSION["current_user"]) && $_SESSION["current_user_user_id"]) {
				if (isset($kwargs["toDelSrc"])) {
					$post = new Post;
					$post->delete_post($kwargs);
					// if (posted = 1)
					// 	$_SESSION["index_posts"] = "";
				}
			}
			else
				echo "Need to connect before publishing a post";
		}
		catch (Exception $e) {
			throw new Exception("Error while deleting the post in controller " . $e->getMessage());
		}
	}

	public static function fill_post_info($post_img) {
        $post = new Post;
        try {
            $post_info = $post->get_post_info($post_img);
        } catch (Exception $e) {
            self::$errors[] = 'Error:' . $e->getMessage();
            return (0);
        }
        if ($post_info) {
            $_SESSION['post_img'] = $post_info['image'];
            $_SESSION['post_creator'] = $post_info['login'];
            return (1);
        } else {
            header("Location: index");
        }
    }

    public static function send_notification_email($creator_id, $post_img) {
        $user = new User;
        $allowed_notif = $user->get_notification_active($creator_id);
        if ($allowed_notif) {
            $creator_notif_email = $user->get_notification_email($creator_id);
            $to = "gabriele_Virga@hotmail.com";
            // $to = $creator_notif_email;
            $from = "gvirga@student.s19.be";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: '.$from. "\r\n";
            $subject = "Someone commented on your post";
            $link = "http://localhost:8080/Camagru/comments&post_img=". $post_img . "&";
            $message = '<h1>Someone commented your post!</h1>
                        <img src="'.$post_img . '"/>
                <a href="' . $link . '"> localhost:8080/Camagru/verif </a>';
            if (mail($to, $subject, $message, $headers)) {
                return (1);
            } else {
                return (0);
            }
        } else {
            return (0);
        }
    }

	public static function get_comments() {
	    try {
            $comment = new Comment;
            $post = New Post;
            $post_id = $post->get_post_id($_SESSION['post_img']);
            $all_comments = $comment->get_post_comments($post_id['post_id']);
            return ($all_comments);
        } catch (Exception $e) {
	        self::$errors[] = $e->getMessage();
	        return (0);
        }
    }

    public static function create_comment() {
        if (input_useable($_SESSION, 'current_user') && input_useable($_SESSION, 'post_img')) {
            if (input_useable($_POST, 'message')) {
                try {
                    $comment = new Comment;
                    $post = new Post;
                    $user = new User;
                    $user_id = $user->get_user_id($_SESSION['current_user']);
                    $post_id = $post->get_post_id($_SESSION['post_img']);
                    $creator_id = $post->get_user_id($post_id['post_id']);
                    if ($user_id && $post_id && $creator_id) {
                        $msg = $_POST['message'];
                        $comment->create_comment($user_id['user_id'], $post_id['post_id'], $msg);
                        $_SESSION['success'] = $msg;
                        self::send_notification_email($creator_id['user_id'], $_SESSION['post_img']);
                        return (1);
                    }
                    else {
                        self::$errors[] = 'Invalid user or post';
                        return (0);
                    }
                } catch (Exception $e) {
                    self::$errors[] = 'Error:' . $e->getMessage();
                    return (0);
                }
            } else {
                    self::$errors[] = 'You can\'t post a empty comment';
                    return (0);
            }
        } else {
            self::$errors[] = 'You have to be connected to comment';
            return (0);
        }
    }

    public static function create_like()
    {
        if (input_useable($_SESSION, 'current_user')) {
            if (input_useable($_POST, 'post_img')) {
                try {
                    $like = new Like;
                    $user = new User;
                    $user_id = $user->get_user_id($_SESSION['current_user']);
                    $post_id = $_POST['post_img'];
                    if ($user_id && $post_id) {
                        if ($like->exist($user_id['user_id'], $post_id)) {
                            return $like->toggle_like($user_id['user_id'], $post_id);
                        } else {
                            $like->create_like($user_id['user_id'], $post_id);
                            return (1);
                        }
                    }
                    else {
                        self::$errors[] = 'Invalid user or post';
                        return (0);
                    }
                } catch (Exception $e) {
                    self::$errors[] = 'Error:' . $e->getMessage();
                    return (0);
                }
            } else {
                self::$errors[] = 'You can\'t post a empty comment';
                return (0);
            }
        } else {
            self::$errors[] = 'You have to be connected to Like';
            return (0);
        }
    }

    public static function get_likes()
    {
        try {
            $like = new Like;
            $post = New Post;
            $post_id = $post->get_post_id($_SESSION['post_img']);
            $nb_likes = $like->get_post_nblikes($post_id['post_id']);
            return ($nb_likes);
        } catch (Exception $e) {
            self::$errors[] = $e->getMessage();
            return (0);
        }
    }
}