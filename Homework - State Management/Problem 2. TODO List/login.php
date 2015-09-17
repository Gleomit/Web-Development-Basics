<?php
    if (isset($_POST['username'])) {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);

        include_once 'db.php';
        $db_access = new todo_db();

        $isValid = $db_access->isUserValid($username, $password);

        if ($isValid != false) {
            session_start();

            $_SESSION['username'] = $isValid['username'];
            $_SESSION['user_id'] = $isValid['user_id'];

            header('Location: list.php');
            die;
        }

        $errorMsg = 'Invalid login.';
    }

    include_once 'partials/header.php';
?>
    <body>
        <?php if (isset($errorMsg)) : ?>
            <h2>Error: <?php echo $errorMsg ?></h2>
        <?php endif ?>

        <h1>Please login:</h1>
        <form method="post">
            Username: <input type="text" name="username" />
            <br />
            Password: <input type="password" name="password" />
            <br />
            <input type="submit" value="Login" />
        </form>
    </body>
<?php include_once 'partials/footer.php'; ?>
