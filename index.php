<?php

require("controllers/RouteController.class.php");
require_once("controllers/Controller.class.php");
require_once("controllers/UserController.class.php");

Route::set("index", function() {
    require_once("views/header.module.php");
    require_once("views/index.view.php");
    require_once("views/footer.module.php");
});

Route::set("sign_up", function() {
    UserController::createView("create_user_form");
});

if (isset($_POST) && array_key_exists("submit_create", $_POST)) {
    UserController::create_user($_POST['login'], $_POST['email'], $_POST['password']);
}

Route::set("create_post", function() {
    PostsController::create_post("assets/post_imgs/aze.png");
});

if (!in_array($_GET["url"], Route::$validRoutes)) {
    require_once("views/header.module.php");
    require_once("views/index.view.php");
    require_once("views/footer.module.php");
}
