<?php

session_start();

class PostsController extends Controller {
    // display post
    // create post
    public static function create_post($image) {
        if(isset($image)) {
            $kwargs = [
                "user_id" => $_SESSION["current_user"]->$user_id,
                "image" => $image
            ];
            Post::create_post($kwargs);
        }
        require_once(post_view.php);
    }
    // delete post
}