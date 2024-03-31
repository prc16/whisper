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
    <link rel="stylesheet" href="../profile/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="icon" type="image/gif" href="../images/whisper-logo-square.png">

</head>

<body>
    <div class="web-container">
        <!--------------- left sidebar --------------->
        <div class="left-sidebar">
            <?php include_once '../sidebar-left/content.php'; ?>
        </div>

        <!--------------- main content--------------->
        <div class="main-content">
            <div id="profile-container">
                <div id="profile-info">
                    <img id="profile-picture" class="profile-picture" src="../images/Default_Profile.jpg" alt="Profile Picture">
                    <p id="profile-username" class="username">Loading...</p>
                </div>
                <div id="edit-options">
                    <button onclick="editProfilePicture()">Edit Picture</button>
                    <button onclick="editUsername()">Edit Username</button>
                </div>
            </div>

        </div>

        <script src="script.js"></script>
        <script src="../functions/profileInfo.js"></script>
        <!--------------- right sidebar --------------->
        <div class="right-sidebar">
            <?php include_once '../sidebar-right/content.php'; ?>
        </div>

    </div>
</body>

</html>