<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Profile</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div id="profile-container">
    <div id="profile-info">
        <img id="profile-picture" src="../images/default_profile_picture.png" alt="Profile Picture">
        <p id="username">Loading...</p>
    </div>
    <div id="edit-options">
        <button onclick="editProfilePicture()">Edit Picture</button>
        <button onclick="editUsername()">Edit Username</button>
    </div>
</div>

<script src="script.js"></script>
