<?php

require("controllers/Route.class.php");

// var_dump($_GET);
// var_dump(Route::$validRoutes);

Route::set("index", function() {
    require_once("views/header.module.php");
    require_once("views/index.view.php");
    require_once("views/footer.module.php");
});

// Route::set()

if (!in_array($_GET["url"], Route::$validRoutes)) {
    require_once("views/header.module.php");
    require_once("views/index.view.php");
    require_once("views/footer.module.php");
}

if (isset($_POST) && array_key_exists("submit_create", $_POST)) {

}