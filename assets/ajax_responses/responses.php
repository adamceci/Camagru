<?php

if(isset($_GET)) {
    if (array_key_exists("toDelSrc", $_GET)) {
        try {
            PostsController::delete_post($_GET);
            echo "OK";
        }
        catch (Exception $e) {
            echo "FAIL";
        }
    }
    else if (array_key_exists("toPubSrc", $_GET)) {
        try {
            PostsController::publish_post($_GET);
            echo "OK";
        }
        catch (Exception $e) {
            echo "FAIL";
        }
    }
    else if (array_key_exists("changePageTo", $_GET)) {
        try {
            $_SESSION['current_page'] = $_GET["changePageTo"];
            PostsController::display_index_posts($_SESSION['current_page']);
            echo "OK";
        }
        catch (Exception $e) {
            echo "FAIL";
        }
    }
}

if (isset($_POST)) {
    if (input_useable($_POST, "array_images")) {
        try {
            PostsController::create_montage();
            PostsController::display_user_posts();
            echo "OK";
        }
        catch (Exception $e) {
            echo "FAIL";
        }
    }
    if (input_useable($_POST, "src_cam_img")) {
        try {
            PostsController::upload("tmp_pics/");
            echo "OK";
        }
        catch (Exception $e) {
            echo "Couldn't upload this picture";
        }
    }
}