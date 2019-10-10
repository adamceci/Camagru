<?php

require("controllers/Route.controller.php");
require_once("controllers/Controller.class.php");


Route::set("index", function() {
    require_once("views/header.module.php");
    require_once("views/index.view.php");
    require_once("views/footer.module.php");
});

Route::set("sign_up", function() {
    User::createView("create_user_form");
});

if (!in_array($_GET["url"], Route::$validRoutes)) {
    require_once("views/header.module.php");
    require_once("views/index.view.php");
    require_once("views/footer.module.php");
}

if (isset($_POST) && array_key_exists("submit_create", $_POST)) {

}