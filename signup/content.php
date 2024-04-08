<?php include_once '../topbar-middle/content.php'; ?>
<div id="signup-container">
    <form id="signup-form" method="POST" action="../signup/server.php">
        <div id="signupFormErrorMessage" class="errorMessage"></div>
        <input type="text" id="signup_username" name="signup_username" placeholder="Username" required>
        <input type="password" id="signup_password" name="signup_password" placeholder="Password" required>
        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm Password" required>
        <input type="submit" value="Sign Up" class="btn">
    </form>
</div>
<script src="../signup/script.js"></script>
