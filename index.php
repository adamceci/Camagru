<?php

session_start();
require_once("assets/macros/errors.php");
require("controllers/Routes.class.php");
require_once("controllers/Controller.class.php");
require_once("controllers/Users.class.php");
require_once("controllers/Posts.class.php");

$_SESSION["index_page"] = 1;
$_SESSION["nb_pages"] = 1;

if (array_key_exists("email", $_GET) && array_key_exists("hash", $_GET)) {
    echo UsersController::activate_account($_GET["email"], $_GET["hash"]);
}

Route::set("index", function() {
    PostsController::display_index_posts($_SESSION["index_page"]);
});

Route::set("sign_up", function() {
    UsersController::createView("create_user_form");
});

Route::set("login", function () {
    UsersController::createView("login");
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
        // Controller::createView("montage");
});

Route::set("success_upload", function() {
    if (!isset($_SESSION["current_user"]))
        Controller::createView("only_to_members");
    else
        Controller::createView("success_upload");
});

if (isset($_POST) && array_key_exists("submit_create", $_POST)) {
    echo UsersController::create_user($_POST);
}

if (isset($_POST) && array_key_exists("submit_login", $_POST)) {
    echo UsersController::login($_POST);
}

if (isset($_POST) && array_key_exists("submit_create_post", $_POST)) {
    PostsController::create_post($_POST);
}

if (!in_array($_GET["url"], Route::$validRoutes)) {
    PostsController::display_index_posts($_SESSION["index_page"]);
}