<?php
    session_start();
    if(!isset($_SESSION["user_id"])) {
        header('Location: login.php');
        die;
    }

    if(isset($_GET['todo_id'])) {
        include_once 'db.php';

        $db_access = new todo_db();

        if($db_access->deleteTodoItem($_SESSION['user_id'], $_GET['todo_id'])) {
            header('Location: list.php');
            die;
        } else {
            header('Location: list.php');
            die;
        }
    } else {
        header('Location: list.php');
        die;
    }
?>