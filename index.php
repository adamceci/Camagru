<?php

session_start();
require_once("assets/macros/errors.php");
require("controllers/Controller.class.php");
require_once("controllers/Comments.interface.php");
require_once("controllers/Users.class.php");
require_once("controllers/Posts.class.php");
require_once("controllers/Webcam.class.php");
require("controllers/Routes.class.php");

$_SESSION["current_page"] = 1;
$_SESSION["nb_pages"] = 1;

if (isset($_GET) && array_key_exists("email", $_GET) && array_key_exists("hash", $_GET)
    && array_key_exists("login", $_GET)) {
    UsersController::activate_account($_GET["email"], $_GET['login'], $_GET["hash"]);
}

Route::set("ajax", function() {
    require_once("assets/ajax_responses/status_ajax.php");
});

Route::set("index", function() {
    PostsController::display_index_posts($_SESSION["current_page"]);
});

Route::set("profile", function () {
    UsersController::template_profile();
});

Route::set('comments', function () {
    if (isset($_GET) && array_key_exists('post_img', $_GET) && !empty($_GET['post_img'])) {
        PostsController::fill_post_info($_GET['post_img']);
        PostsController::$info = PostsController::get_comments();
        PostsController::template_comment();
    }
});

if (!array_key_exists("current_user", $_SESSION)) {
    Route::set("login", function () {
        UsersController::template_login();
    });
    Route::set("sign_up", function() {
        UsersController::template_sign_up();
    });
}

Route::set("ajax", function() {
    require_once("assets/ajax_responses/status_ajax.php");
});

Route::set("logout", function () {
    UsersController::logout();
});

// Route::set("only_to_members", function() {
    // Controller::createView("")
// });

Route::set("montage", function() {
    if (!isset($_SESSION["current_user"]))
        Controller::createView("only_to_members");
    else
        PostsController::display_user_posts();
});

Route::set("success_upload", function() {
    if (!isset($_SESSION["current_user"]))
        Controller::createView("only_to_members");
    else
        Controller::createView("success_upload");
});

Route::set("webcam", function () {
   Webcam::createView("webcam");
});

Route::set("update", function() {

});

if (isset($_POST) && (array_key_exists("submit_create_post", $_POST) || array_key_exists("save", $_POST))) {
    PostsController::create_post($_POST);
}

if (!in_array($_GET["url"], Route::$validRoutes)) {
    PostsController::display_index_posts($_SESSION["current_page"]);
}