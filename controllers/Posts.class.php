<?php

require_once("models/Model.class.php");
require_once("models/Comment.class.php");
require_once("models/Like.class.php");
require_once("models/PostsModel.class.php");

date_default_timezone_set("Europe/Brussels");


class PostsController extends Controller implements Comments, Likes {

	public static function template_file_filters() {
		self::createModule("top_html_tags", 0);
		self::createModule("header", 0);
		self::createModule("montage_title", 0);
		self::createModule("open_container_tags", 0);
		self::createModule("open_main_tag", 0);
		self::createModule("montage_file", 0);
		self::createModule("filters", 0);
		self::createModule("close_main_tag", 0);
        self::createModule("montage_side", 1);
		self::createModule("close_container_tags", 0);
		self::createModule("footer", 0);
		self::createModule("script_filters", 0);
		self::createModule("script_montage_file", 0);
		self::createModule("script_save_post", 0);
		self::createModule("script_post_delete", 0);
        self::createModule("bottom_html_tags", 0);
	}

	public static function upload($dir_name) {
		try {
			if (input_useable($_POST, "upload_image")) {
				if (parent::upload_file($dir_name) == 0) {
					self::$errors[] = "couldn't upload image";
					$_SESSION['errors'] = self::get_errors();
					return (0);
				}
				self::$info = self::get_user_images();
				self::template_file_filters();
				return (1);
			}
			else if (input_useable($_POST, "src_cam_img")) {
				$baseFromJavascript = $_POST["src_cam_img"];
				$data = explode(',', $baseFromJavascript);
				$data_64 = str_replace(' ', '+', $data[1]);
				$bin = base64_decode(strval($data_64));
				$file_name = basename(parent::generate_unique_name("assets/tmp_pics/", "cam.png"));
				if (!file_exists("assets/tmp_pics/"))
					mkdir("assets/tmp_pics/", 0755, true);
				file_put_contents("assets/tmp_pics/" . $file_name, $bin);
				$_SESSION["tmp_file_name"] = $file_name;
				return (1);
			}
			else
				self::$errors[] = 'upload_image vide';
				$_SESSION['errors'] = self::get_errors();
				return (0);
		}
		catch (Exception $e) {
			self::$errors[] = $e->getMessage();
			$_SESSION['errors'] = self::get_errors();
		}
	}

	private static function apply_filters($img_srcs, $extension) {
		if ($extension == "png")
			$base_img = imagecreatefrompng($img_srcs[0]);
		else
			$base_img = imagecreatefromjpeg($img_srcs[0]);
		$final_img = imagecreatetruecolor(imagesx($base_img), imagesy($base_img));
		imagealphablending($final_img, TRUE);
		imagesavealpha($final_img, TRUE);
		imagecopyresampled($final_img, $base_img, 0, 0, 0, 0, imagesx($base_img), imagesy($base_img), imagesx($base_img), imagesy($base_img));
		array_shift($img_srcs);
		if ($img_srcs) {
			foreach($img_srcs as $img_src) {
				$filter = imagecreatefrompng($img_src);
				imagecopyresampled($final_img, $filter, 0, 0, 0, 0, imagesx($base_img), imagesy($base_img), imagesx($filter), imagesy($filter));
				imagedestroy($filter);
			}
		}
		imagedestroy($base_img);
		return ($final_img);
	}

	private static function upload_to_folder($file, $path) {
		if (!file_exists($path))
			mkdir($path, 0755, true);
		$now = time();
		while(file_exists($file_name = $path . $_SESSION["current_user_user_id"] . "-" . $now . ".png"))
			$now++;
		imagepng($file, $file_name);
		return ($file_name);
	}

	private static function delete_from_tmp($img_srcs) {
		$path = "assets/tmp_pics/";
		$tmp_to_del = basename(array_shift($img_srcs));
		if (file_exists($path . $tmp_to_del))
			unlink($path . $tmp_to_del);
	}

	public static function create_montage() {
		try {
			if (input_useable($_POST, 'array_images')) {
				$img_srcs = json_decode($_POST["array_images"]);
				$img_srcs = $img_srcs->imagesArray;
				$extension = pathinfo($img_srcs[0])['extension'];
				$final_img = self::apply_filters($img_srcs, $extension);
				$file_name = self::upload_to_folder($final_img, "assets/post_pics/");
				self::delete_from_tmp($img_srcs);
				$post = new Post;
				$kwargs["user_id"] = $_SESSION["current_user_user_id"];
				$kwargs["image"] = basename($file_name);
				$post->create_post($kwargs);
				if ($_POST["to_pub"] == 1) {
					$kwargs["posted_time"] = date("Y-m-d H:i:s");
					$kwargs["toPubSrc"] = $kwargs["image"];
					$post->publish_post($kwargs);
				}
			}
			else
				echo "<p class='error'>'array_image' key might not exist in " . '$_POST ' . "variable or be empty</p>";
		}
		catch (Exception $e) {
			echo "<p class='error'>Error : " . $e->getMessage() . "</p>";
		}
	}

	public static function template_montage() {
		self::createModule("top_html_tags", 0);
		self::createModule("header", 0);
		self::createModule("montage_title", 0);
		self::createModule("open_container_tags", 0);
		self::createModule("open_main_tag", 0);
        self::createModule("montage_main", 0);
		self::createModule("filters", 0);
		self::createModule("close_main_tag", 0);
        self::createModule("montage_side", 1);
		self::createModule("close_container_tags", 0);		
        self::createModule("footer", 0);
		self::createModule("scripts_gallery", 0);
		self::createModule("script_post_delete", 0);
        self::createModule("bottom_html_tags", 0);
    }

	private static function get_index_posts($page) {
		$post = new Post;
		$_SESSION["nb_pages"] = (int)$post->get_nb_pages();
		$offset = 6 * ($page - 1);
		$index_posts = $post->get_index_posts($offset);
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
			if (!empty(self::$info)) {
				foreach (self::$info as $post) {
					$nb_like_output = $likes->get_post_nblikes($post['post_id']);
					$nb_comments_output = $comments->get_nbr_comments($post['post_id']);
					if (array_key_exists('nb_comments', $nb_comments_output[0]))
    					$tmp['nb_comments'][] = $nb_comments_output[0]['nb_comments'];
                    else
                        $tmp['nb_comments'][] = "0";
                    if (array_key_exists('nb_likes', $nb_like_output[0]))
                        $tmp['nb_likes'][] = $nb_like_output[0]['nb_likes'];
                    else
                        $tmp['nb_likes'][] = "0";
					if (input_useable($_SESSION, 'current_user')) {
						$user = new User;
						$user_id = $user->get_user_id($_SESSION['current_user']);
                        $tmp['users_likes_it'][] = $likes->is_active($user_id['user_id'], $post['post_id']);
					} else {
						$tmp['users_likes_it'][] = 0;
					}
				}
                self::$info['nb_comments'] = $tmp['nb_comments'];
                self::$info['nb_likes'] = $tmp['nb_likes'];
                self::$info['users_likes_it'] = $tmp['users_likes_it'];
			}
			parent::template_index();
		}
		catch (Exception $e) {
			self::$errors[] = $e->getMessage();
			$_SESSION['errors'] = self::get_errors();
		}
	}

	public static function get_user_images() {
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
			self::$errors[] = $e->getMessage();
			$_SESSION['errors'] = self::get_errors();
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

	// publish post
	public static function publish_post(array $kwargs) {
		try {
			if (isset($_SESSION["current_user"])) {
				if (isset($kwargs["toPubSrc"])) {
					$kwargs["posted_time"] = date("Y-m-d H:i:s");
					$post = new Post;
					$post->publish_post($kwargs);
				}
				else
					echo "<p class='error'>Missing source of the image to be published</p>";
			}
			else
				echo "<p class='error'>Need to connect before publishing a post</p>";
		}
		catch (Exception $e) {
			echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
		}
	}

    // delete post
	public static function delete_post(array $kwargs) {
		try {
			if (isset($_SESSION["current_user"])) {
				if (isset($kwargs["toDelSrc"])) {
					if ($_SESSION["current_user_user_id"] == substr(basename($kwargs["toDelSrc"]), 0, 1)) {
						$post = new Post;
						$post->delete_post($kwargs);
					}
					else
						echo "<p class='error'>You can't delete another user's post !</p>";
				}
			}
			else
				echo "<p class='error'>Need to connect before deleting a post</p>";
		}
		catch (Exception $e) {
			echo "<p class='error'>Error : " . $e->getMessage() . "</p>";
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
            $_SESSION['post_creator_pic'] = $post_info['profile_pic'];
            return (1);
        } else {
            header("Location: index");
        }
    }

    public static function send_notification_email($creator_id, $post_img) {
        $user = new User;
		$allowed_notif = $user->get_notification_active($creator_id);
        if ($allowed_notif["notification_active"] !== "0") {
            $creator_notif_email = $user->get_notification_email($creator_id)['notification_email'];
            $to = $creator_notif_email;
            $from = "aceciora@student.s19.be";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: '.$from. "\r\n";
			$subject = "Someone commented on your post";
			$link = "http://localhost:8080" . $_SESSION["for_comm_mail"] . "/comments&post_img=" . $post_img . "&";
			// $link = "http://localhost:8080/camagru_mvc/comments&post_img=". $post_img . "&";
            $message = '<h1>Someone commented your post!</h1>
                        <img src="'.$post_img . '"/>
                <a href="' . $link . '"> localhost:8080/camagru/verif </a>';
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