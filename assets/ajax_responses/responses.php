<?php

require_once("../../controllers/Posts.class.php");

if(isset($_GET)) {
    if (array_key_exists("toDelSrc", $_GET)) {
        try {
            PostsController::delete_post($_GET);
            echo "OK";
        }
        catch (exception $e) {
            echo "FAIL";
        }
    }
    else if (array_key_exists("toPubSrc", $_GET)) {
        try {
            PostsController::publish_post($_GET);
            echo "OK";
        }
        catch (exception $e) {
            echo "FAIL";
        }
    }
}