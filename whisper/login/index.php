<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>

    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/php-includes/styles.php'; ?>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/php-includes/scripts.php'; ?>

</head>

<body>
    <div class="web-container">
        <!--------------- left sidebar --------------->
        <header class="left-sidebar">
            <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/whisper/sidebar-left/content.php'; ?>
        </header>

        <!--------------- main content--------------->
        <main class="main-content">

            <!--------------- middle feed --------------->
            <div class="middle-feed">
                <?php include_once __DIR__ . '/content.php'; ?>
            </div>

            <!--------------- right sidebar --------------->
            <div class="right-sidebar">
                <?php include_once __DIR__ . '/content-right.php'; ?>
            </div>
        </main>
    </div>

</body>

</html>