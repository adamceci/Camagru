<?php

require_once("models/Model.class.php");
require_once("models/PostsModel.class.php");
// session_start();

class PostsController extends Controller {
	// display post
	public static function display_posts($page) {
		try {
			if (!isset($page))
				$page = 1;
			$post = new Post;
			$limit = 6;
			$offset = 6 * ($page - 1);
			$_SESSION["index_posts"] = $post->get_posts($limit, $offset);
			parent::createView("index");
		}
		catch (Exception $e) {
			throw new Exception("Error while getting the posts in PostsController " . $e->getMessage());
		}
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