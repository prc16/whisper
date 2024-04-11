<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/whisper/topbar-middle/content.php'; ?>
<div id="login-container">
    <form id="login-form" method="POST">
        <div id="loginFormErrorMessage" class="errorMessage"></div>
        <input type="text" id="login_username" name="login_username" placeholder="Username" autocomplete="username" required>
        <input type="password" id="login_password" name="login_password" placeholder="Password" autocomplete="current-password" required>
        <input type="submit" value="Log In" class="btn">
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('login-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the form from submitting

            var username = document.getElementById('login_username').value;
            var password = document.getElementById('login_password').value;

            const formData = new FormData();
            formData.append("login_username", username);
            formData.append("login_password", password);

            // Send the form data to the server using Fetch API
            fetch('/server/login', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        // Successful login
                        window.location.href = '/home';
                    } else {
                        // Parse JSON response
                        return response.json().then(data => {
                            // Server returned an error, display the error message
                            document.getElementById('loginFormErrorMessage').innerText = data.message;
                            console.log(data.message);
                        });
                    }
                })
                .catch(error => {
                    console.error('There was a problem with your fetch operation:', error);
                });
        });
    });
</script>