<?php
    session_start();
    if(!isset($_SESSION["user_id"])) {
        header('Location: login.php');
        die;
    }

    if(isset($_POST['todo_text'])) {
        include_once 'TodoDb.php';

        $db_access = new TodoDb();

        if($db_access->addTodoItem($_SESSION['user_id'], $_POST['todo_text'])) {
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
