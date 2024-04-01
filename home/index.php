<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whisper - Home</title>

    <link rel="stylesheet" href="../home/style.css">
    <link rel="stylesheet" href="../sidebar-left/style.css">
    <link rel="stylesheet" href="../bottom-left/style.css">
    <link rel="stylesheet" href="../sidebar-right/style.css">
    <link rel="stylesheet" href="../posts/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="icon" type="image/gif" href="../images/whisper-logo-square.png">

    <script src="../posts/script.js"></script>

</head>

<body>
    <div class="web-container">
        <!--------------- left sidebar --------------->
        <header class="left-sidebar">
            <?php include_once '../sidebar-left/content.php'; ?>
        </header>

        <!--------------- main content--------------->
        <main class="main-content">

            <!--------------- home feed --------------->
            <div class="home-feed">
                <?php include_once '../posts/content.php'; ?>
            </div>

            <!--------------- right sidebar --------------->
            <div class="right-sidebar">
                <?php include_once '../sidebar-right/content.php'; ?>
            </div>
        </main>
    </div>

</body>

</html>