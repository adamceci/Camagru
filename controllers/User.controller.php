<?php

namespace School19\Camagru\Controller;

use School19\Camgru\Model as Model;

class User extends Controller {
    public function create_user($login, $email, $password) {
        $user = new Model\User;
        if ($user->create_user()) {
            parent::createView("create_user_succ");
        }
    }
}