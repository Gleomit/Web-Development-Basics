<?php
require_once 'index.php';

if(isset($_POST['username']) && isset($_POST['password'])) {
    try {
        $user = $_POST['username'];
        $pass = $_POST['password'];

        if($app->register($user, $pass)) {
            $app->login($user, $pass);

            if($app->isLogged()) {
                header("Location: profile.php");
                exit;
            }
        }
    } catch(Exception $e) {
        echo $e->getMessage();
    }
}

loadTemplate("register");