<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whisper - Home</title>
    
    <link rel="stylesheet" href="../home/home.css">
    <link rel="stylesheet" href="../sidebar-left/style.css">
    <link rel="stylesheet" href="../bottom-left/style.css">
    <link rel="stylesheet" href="../sidebar-right/style.css">
    <link rel="stylesheet" href="../posts/posts.css">
    <link rel="stylesheet" href="../profile/style.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link rel="icon" type="image/gif" href="../images/whisper-logo-small.png">
    
    <script src="../posts/script.js"></script>
</head>

<body>
    <div class="web-container">
        <!--------------- left sidebar --------------->
        <div class="left-sidebar">
            <?php include_once '../sidebar-left/content.php'; ?>
        </div>

        <!--------------- main content--------------->
        <div class="main-content">
            <?php include_once '../posts/content.php'; ?>
        </div>

        <!--------------- right sidebar --------------->
        <div class="right-sidebar">
            <?php include_once '../sidebar-right/content.php'; ?>
        </div>

    </div>
</body>

</html>