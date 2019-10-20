<?php

require_once("models/Model.class.php");
require_once("models/PostsModel.class.php");
// session_start();

class PostsController extends Controller {
	// display post
	public static function display_posts() {
		$post = new Post;
		$limit = 6;
		$offset = 4;
		$array = $post->get_posts($limit, $offset);
		// var_dump($array);
		parent::createView("index");
		// require_once("views/index.view.php");
	}
    // create post
    public static function create_post($kwargs) {
        if (array_key_exists("image", $kwargs)) {
			$post = new Post;
			$kwargs["user_id"] = $_SESSION["current_user_user_id"];
            $post->create_post($kwargs);
		}
		else {
			echo "Please select an image";
		}
		// if filter doesn't exist ? -> todo
        // require_once(post_view.php);
    }
    // delete post
}