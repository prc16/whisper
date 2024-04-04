<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update Profile</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <?php include_once '../php-includes/styles.php'; ?>
    <?php include_once '../php-includes/scripts.php'; ?>

    <link rel="stylesheet" href="style.css">

</head>

<body>
    <div class="web-container">
        <!--------------- left sidebar --------------->
        <header class="left-sidebar">
            <?php include_once '../sidebar-left/content.php'; ?>
        </header>

        <!--------------- main content--------------->
        <main class="main-content">

            <!--------------- middle feed --------------->
            <div class="middle-feed">
                <?php include_once './content.php'; ?>
            </div>

            <!--------------- right sidebar --------------->
            <div class="right-sidebar">
                <?php include_once '../sidebar-right/content.php'; ?>
            </div>
        </main>
    </div>

</body>

</html>