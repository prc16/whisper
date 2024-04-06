<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <?php include_once '../php/style-includes.php'; ?>
    <?php include_once '../php/script-includes.php'; ?>

    <script src="./script.js"></script>

</head>

<body>
    <div class="web-container">
        <!--------------- left sidebar --------------->
        <nav class="left-sidebar">
            <?php include_once '../sidebar-left/content.php'; ?>
        </nav>

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