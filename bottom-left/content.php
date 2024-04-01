<div class="bottom-container">
    <?php include_once '../profile-container/content.php'; ?>
    <div class="bottom-container-buttons">
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
            echo '<a href="../logout/logout.php" class="logout-btn">Logout</a>';
        } else {
            // Display login and signup buttons when not logged in
            echo '<a href="../login/" class="login-btn">Login</a>';
            echo '<a href="../signup/" class="signup-btn">Sign Up</a>';
        }
        ?>
    </div>

</div>