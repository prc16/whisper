<?php include_once '../topbar-middle/content.php'; ?>
<div id="login-container">
    <form id="login-form" method="POST" action="../login/server.php">
        <div id="loginFormErrorMessage" class="errorMessage"></div>
        <input type="text" id="login_username" name="login_username" placeholder="Username" required>
        <input type="password" id="login_password" name="login_password" placeholder="Password" required>
        <input type="submit" value="Sign Up" class="btn">
    </form>
</div>
<script src="../login/script.js"></script>