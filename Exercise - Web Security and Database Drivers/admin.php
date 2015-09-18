<?php
    include_once 'Localization.php';
    include_once 'Db.php';
    include_once 'translations.php';

    $db = Db::getInstance();

    if(isset($_POST['text_bg'])) {
        foreach($_POST['text_bg'] as $key => $value) {

            $updateTranslations = $db->prepare("UPDATE translations SET text_bg = ? WHERE id = ?");

            $data = array($value, $key);

            $updateTranslations->execute($data);
        }
    }

    $resultTranslations = $db->query("SELECT id, tag, text_en, text_bg FROM translations");

    $translations = $resultTranslations->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
    <head>

    </head>
    <body>
        <form method="post">
            <?php
                foreach($translations as $translation) { ?>
                <div class="source-translation">
                    <?php echo $translation['text_' . Localization::$LANG_DEFAULT]; ?>
                </div>
                <textarea name="<?php echo 'text_bg[' . $translation['id'] . ']'; ?>"><?php echo $translation['text_bg']; ?></textarea>
            <?php
                }
            ?>
            <input type="submit" value="Save">
        </form>
    </body>
</html>
