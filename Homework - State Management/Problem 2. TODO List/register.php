<?php
    if (isset($_POST['username'])) {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);

        include_once("TodoDb.php");
        $db_access = new TodoDb();

        $errorMessage = null;

        $registrationResult = $db_access->createUser($username, $password);

        if($registrationResult === true) {
            header('Location: login.php');
            die;
        } else {
            $errorMessage = $registrationResult;
        }
    }

    include_once 'partials/header.php';
?>

<body>
<?php if (isset($errorMessage)) : ?>
    <h2>Error: <?php echo $errorMessage['message']; ?></h2>
<?php endif ?>
    <h1>Register:</h1>

    <form method="post">
        Username: <input type="text" name="username"/>
        <br/>
        Password: <input type="password" name="password"/>
        <br/>
        <input type="submit" value="Register"/>
    </form>
</body>
<?php
    include_once 'partials/footer.php';
?>


