<?php
session_start();

if (!isset($_POST["name"]) && !isset($_SESSION['username'])) {
    header('Location: index.php?error=nameNotSet');
    die();
}

if(isset ($_POST["name"])) {
    unset($_SESSION["numberToGuess"]);
    unset($_SESSION["username"]);
    unset($_SESSION["triesCount"]);
}

if (!isset($_SESSION["numberToGuess"])) {
    $_SESSION["numberToGuess"] = rand(1, 100);
    $_SESSION["username"] = htmlspecialchars($_POST["name"]);
    $_SESSION["triesCount"] = 0;
}

include_once 'Messages.php';

$username = $_SESSION["username"];
$triesCount = intval($_SESSION['triesCount']);

if (isset($_POST['guestNumber'])) {
    $triesCount += 1;
    $_SESSION['triesCount'] = $triesCount;
}

$guestNumber = isset($_POST["guestNumber"]) ? $_POST["guestNumber"] : "notSet";

$result = 1;

$isValid = false;

if (is_numeric($guestNumber)) {
    $guestNumber = intval($guestNumber);

    if ($guestNumber >= 1 && $guestNumber <= 100) {
        $isValid = true;

        $numberToGuess = intval($_SESSION["numberToGuess"]);

        if ($guestNumber == $numberToGuess) {
            $result = 0;
        } elseif ($guestNumber < $numberToGuess) {
            $result = -1;
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Currently playing <?php echo $username; ?></title>
</head>
<body>
<?php
if ($result === 0) {
    ?>
    <span><?php echo Messages::GUESSED . $username; ?></span>
    <form action="index.php">
        <input type="submit" value="Play Again"/>
    </form>
    <?php
    unset($_SESSION['numberToGuess']);
    unset($_SESSION['username']);
    unset($_SESSION['triesCount']);
} else { ?>
    <form method="POST">
        <input type="text" placeholder="Enter a number in range[1 ... 100]" id="guestNumber" name="guestNumber"/>
    </form>
    <?php
}

if ($triesCount > 0) {
    if ($isValid == true) {
        if ($result == -1) { ?>
            <p><?php echo Messages::UP ?></p>
            <?php
        } elseif ($result == 1) {
            ?>
            <p><?php echo Messages::DOWN ?></p>
            <?php
        }
    } else { ?>
        <p><?php echo Messages::INVALID_NUMBER ?></p>
        <?php
    }
}
?>
</body>
</html>