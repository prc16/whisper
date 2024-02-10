<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whisper - Home</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="../navbar/navbar.css">
    <link rel="stylesheet" href="../posts/posts.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/gif" href="../.github/whisper-logo.png">
    <script src="../posts/script.js"></script>
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="web-container">
    <!--------------- left sidebar --------------->
    <div class="left-sidebar">
        <div class="imp-links">
            <a href="#"><i class="fa-solid fa-house"></i>home</a></br>
            <a href="#"><i class="fa-solid fa-user"></i>profile</a></br>
            <a href="#"><i class="fa-solid fa-user-group"></i></i>Friends</a></br>
            <a href="#"><i class="fa-solid fa-envelope"></i>Messages</a></br>    
        </div>
        <hr>
    </div>

    <!--------------- main content--------------->
    <div class="main-content">
        
    <?php include '../posts/posts.php'; ?>
    <?php include '../posts/posts.php'; ?>
    <?php include '../posts/posts.php'; ?>
    <?php include '../posts/posts.php'; ?>
    <?php include '../posts/posts.php'; ?>
    <?php include '../posts/posts.php'; ?>
    <?php include '../posts/posts.php'; ?>
    <?php include '../posts/posts.php'; ?>

    </div>

    <!--------------- right sidebar --------------->
    <div class="right-sidebar">


    </div>

    </div>
</body>
</html>
