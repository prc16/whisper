<!--------------- profile main --------------->
<div id="profile-main">
    <div class="profile-container-large">
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
        echo '<img id="profile-container-large-picture" src="' . $profilePicture . '">';
        echo '<div id="profile-container-large-username">' . $username . '</div>';
        ?>
    </div>
    <div id="profile-edit-options">
        <button onclick="editProfilePicture()">Edit Picture</button>
        <button onclick="editUsername()">Edit Username</button>
    </div>
</div>