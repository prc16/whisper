<div id="bottom-left-container">
    <div id="bottom-left-profile-container">
        <img id="bottom-left-profile-picture" class="profile-picture" src="../images/Default_Profile.jpg" alt="">
        <div id="bottom-left-profile-username" class="profile-username">Loadiang..</div>
    </div>
    <div id="bottom-left-buttons-container">
        <?php

        // Start the session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if the user is logged in
        if (isset($_SESSION["user_id"])) {
            $loggedIn = true;
            $userId = $_SESSION["user_id"];
        } else {
            $loggedIn = false;
        }

        if ($loggedIn) {
            echo '<a id="bottom-left-buttons-logut" class="logout-btn" href="../logout/logout.php">Logout</a>';
        } else {
            // Display login and signup buttons when not logged in
            echo '<a id="bottom-left-buttons-login" class="login-btn" href="../login/">Login</a>';
            echo '<a id="bottom-left-buttons-signup" class="signup-btn" href="../signup/">Signup</a>';
        }
        ?>
    </div>

</div>