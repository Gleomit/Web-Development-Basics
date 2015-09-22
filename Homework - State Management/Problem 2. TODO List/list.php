<?php
    session_start();
    if(!isset($_SESSION["user_id"])) {
        header('Location: login.php');
        die;
    }

    include_once 'TodoDb.php';

    $db_access = new TodoDb();

    $todos = $db_access->getTodoItems($_SESSION['user_id']);

    $todosView = "";

    foreach($todos as $todo) {
        $todosView .= '<li class="ui-state-default">' . $todo['todo_item'] .
            '<form method="get" action="delete.php"><input name="todo_id" type="hidden" value="' . $todo['id'] .
            '"/><input type="submit" class="btn btn-danger" value="X" /></form></li>';
    }

    include_once 'partials/header.php';
?>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="todolist not-done">
                    <h1>Todos</h1>
                    <form action="add.php" method="post">
                        <input type="text" class="form-control" name="todo_text">
                        <input type="submit" class="btn btn-default form-control" value="Add todo">
                    </form>
                    <hr>
                    <ul class="list-unstyled text-center">
                        <?php  echo $todosView; ?>
                </div>
            </div>
        </div>
    </div>
</body>

<?php
    include_once 'partials/footer.php';
?>