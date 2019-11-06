<?php

function user_ajax_response($method) {
    if ($method != 'login' && $method != 'create_user') {
        return (0);
    }
    UsersController::$method($_POST);
    $errors = Controller::get_errors();
    if (!empty($errors)) {
        foreach ($errors as $error)
            echo "<p class=\"error\">" . $error . "</p>";
        return (0);
    }
    echo "OK";
}

function posts_ajax_response($method) {
    if ($method != 'get_comments' && $method != 'create_comment') {
        echo "Unauthorized method";
        return (0);
    }
    PostsController::$method($_POST);
    $errors = Controller::get_errors();
    if (!empty($errors)) {
        foreach ($errors as $error)
            echo "<p class=\"error\">" . $error . "</p>";
        return (0);
    }
    echo "OK";
}

if (!array_key_exists("current_user", $_SESSION)) {
    if (isset($_GET) && array_key_exists("method", $_GET) && array_key_exists('user', $_GET) && isset($_POST)) {
        user_ajax_response($_GET['method']);
    }
} else {
    if (isset($_GET) && array_key_exists("method", $_GET) && array_key_exists('posts', $_GET) && isset($_POST)) {
        posts_ajax_response($_GET['method']);
    }
}
