<?php

include_once '../database/functions.php';

$conn = getConnection();

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Validate session and action
if (isset($_SESSION['user_id'])) {
    $loggedIn = true;
    $userId = $_SESSION['user_id'];
    $username = getUsername($conn, $userId);
    $profilePicture = getProfilePicture($conn, $userId);
} else {
    $loggedIn = false;
    $username = 'Anonymous';
    $profilePicture = '../images/Default_Profile.jpg';
}
echo '<div id="bottom-left-container">';
echo '  <div id="bottom-left-profile-container">';

echo '      <img id="bottom-left-profile-picture" class="profile-picture" src="' . $profilePicture . '">';
echo '      <div id="bottom-left-profile-username" class="profile-username">' . $username . '</div>';
echo '  </div>';
echo '  <div id="bottom-left-buttons-container">';
if ($loggedIn) {
echo '      <a id="bottom-left-buttons-logout" class="logout-btn" href="../logout/logout.php">Logout</a>';
} else {
    // Display login and signup buttons when not logged in
echo '      <a id="bottom-left-buttons-login" class="login-btn" href="../login/">Login</a>';
echo '      <a id="bottom-left-buttons-signup" class="signup-btn" href="../signup/">Signup</a>';
}
echo '  </div>';
echo '</div>';
