<?php

session_start();
require_once("models/PostsModel.class.php");
require_once("assets/macros/errors.php");
require_once("controllers/Controller.class.php");
require_once("controllers/Comments.interface.php");
require_once("controllers/Likes.interface.php");
require_once("controllers/Users.class.php");
require_once("controllers/Posts.class.php");
require_once("controllers/Routes.class.php");
require_once("assets/utils_functions/input_verification.php");

try {
    $_SESSION["for_comm_mail"] = substr($_SERVER['PHP_SELF'], 0, strlen($_SERVER['PHP_SELF']) - 10);
    $post = new Post;
    $post->first_connect();
}
catch (Exception $e) {
    echo ("click here to create the database : <a href='setup'>setup the database</a>");
    unset($_SESSION["errors"]);
}

if (!isset($_SESSION["current_page"]))
    $_SESSION["current_page"] = 1;

if (!isset($_SESSION["nb_pages"]))
    $_SESSION["nb_pages"] = 1;


if (input_useable($_GET, 'email')
    && input_useable($_GET, 'login')
    && input_useable($_GET,'hash')) {
    UsersController::activate_account($_GET["email"], $_GET['login'], $_GET["hash"]);
}

if (input_useable($_GET, 'url') && $_GET['url'] == 'password_reset'
    && input_useable($_GET, 'email')
    && input_useable($_GET, 'hash')) {
    Route::set('password_reset', function () {
        UsersController::template_password_change();
    });
}

Route::set('setup', function() {
    require_once("config/setup.php");
});

Route::set("ajax", function() {
    require_once("assets/ajax_responses/status_ajax.php");
});

Route::set("responses", function () {
    require_once("assets/ajax_responses/responses.php");
});

Route::set("index", function() {
    PostsController::display_index_posts($_SESSION["current_page"]);
});

Route::set("profile", function () {
    UsersController::template_profile();
    if (array_key_exists('update', $_GET)) {
        if (isset($_POST))
            UsersController::update_user($_POST);
    }
});

Route::set("password_recovery", function() {
    UsersController::template_password_recovery();
});

Route::set('comments', function () {
    if (isset($_GET) && array_key_exists('post_img', $_GET) && !empty($_GET['post_img'])) {
        PostsController::fill_post_info($_GET['post_img']);
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

Route::set("logout", function () {
    UsersController::logout();
});


Route::set("montage", function() {
    if (!input_useable($_SESSION, "current_user"))
        Controller::createView("only_to_members");
    else
        PostsController::display_user_posts();
});

Route::set("montage_two", function() {
    if (array_key_exists('webcam', $_GET)) {
        PostsController::$info = PostsController::get_user_images();
        PostsController::template_file_filters();
    }
    else {
        if (!input_useable($_SESSION, "current_user"))
            Controller::createView("only_to_members");
        else if (PostsController::upload('tmp_pics/') == 0)
            header('Location: montage');
    }
});

Route::set("success_upload", function() {
    if (!input_useable($_SESSION, "current_user"))
        Controller::createView("only_to_members");
    else
        Controller::createView("success_upload");
});

if (!in_array($_GET["url"], Route::$validRoutes)) {
    PostsController::display_index_posts($_SESSION["current_page"]);
}