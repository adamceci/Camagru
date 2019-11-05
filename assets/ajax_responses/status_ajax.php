<?php

function ajax_response($method) {
    UsersController::$method($_POST);
    $errors = Controller::get_errors();
    $success_msgs = Controller::get_success();
    if (!empty($errors)) {
        foreach ($errors as $error)
            echo "<p class=\"error\">" . $error . "</p>";
    } else if (!empty($success_msgs)) {
        foreach ($success_msgs as $success_msg)
            echo "<p class=\"error\">" . $success_msg . "</p>";
    } else {
        return "OK";
    }

}
var_dump($_GET);
var_dump($_POST);
ajax_response($_GET['method']);
