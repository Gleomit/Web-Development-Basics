<?php
    include_once 'translations.php';
?>
<!doctype html>
<html>
    <head>
        <title>Exercise</title>
    </head>

    <body>
        <header>
            <a href="?lang=bg">BG</a> | <a href="?lang=en">EN</a>
            <h1>
                <?php echo __("greeting_header_hello"); ?>
            </h1>
        </header>
        <div id="content">
            <p>
                <?php echo __("welcome_message"); ?>
            </p>
        </div>
    </body>
</html>