<?php

namespace SoftUni\Controllers;

use SoftUni\Helpers\Session;

class Controller
{
    protected function isLogged() {
        return Session::get('id') != null;
    }
}