<?php

function ajax_response($method) {
    UsersController::$method($_POST);
    var_dump($_POST);
    $errors = Controller::get_errors();
    if (!empty($errors)) {
        foreach ($errors as $error)
            echo "<p class=\"error\">" . $error . "</p>";
        return;
    }
    echo "OK";
}

if (!array_key_exists("current_user", $_SESSION)) {
    if (isset($_GET) && array_key_exists("method", $_GET) && isset($_POST)) {
        var_dump("fuck you");
        ajax_response($_GET['method']);
    }
}
