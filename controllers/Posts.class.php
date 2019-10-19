<?php

require_once("models/Model.class.php");
require_once("models/PostsModel.class.php");
// session_start();

class PostsController extends Controller {
    // display post
    // create post
    public static function create_post($kwargs) {
        if (array_key_exists("image", $kwargs)) {
            $kwargs["user_id"] = $_SESSION["current_user"];
            Post::create_post($kwargs);
		}
		else {
			echo "Please select an image";
		}
		// if filter doesn't exist ? -> todo
        // require_once(post_view.php);
    }
    // delete post
}