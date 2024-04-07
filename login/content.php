<?php include_once '../topbar-middle/content.php'; ?>
<div id="login-container">
    <form id="login-form" method="POST" action="../login/server.php">
        <div id="loginFormErrorMessage" class="errorMessage"></div>
        <input type="text" id="login-username" name="login-username" placeholder="Username" required>
        <input type="password" id="login-password" name="login-password" placeholder="Password" required>
        <input type="submit" value="Sign Up" class="btn">
    </form>
</div>

<script>
    document.getElementById('login-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting

        var username = document.getElementById('login-username').value;
        var password = document.getElementById('login-password').value;

        // Send the form data to the server using AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../login/server.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Successful login, redirect to maintenance page
                window.location.href = '../home/';
            } else {
                // Server returned an error, display the error message
                document.getElementById('loginFormErrorMessage').innerText = xhr.responseText;
            }
        };
        xhr.send('login-username=' + encodeURIComponent(username) + '&login-password=' + encodeURIComponent(password));
    });
</script>
