<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/whisper/topbar-middle/content.php'; ?>
<div id="signup-container">
    <form id="signup-form">
        <div id="signupFormErrorMessage" class="errorMessage"></div>
        <input type="text" id="signup_username" name="signup_username" placeholder="Username" autocomplete="username" required>
        <input type="password" id="signup_password" name="signup_password" placeholder="Password" autocomplete="new-password" required>
        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm Password" autocomplete="new-password" required>
        <input type="submit" value="Sign Up" class="btn">
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('signup-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the form from submitting

            var username = document.getElementById('signup_username').value;
            var password = document.getElementById('signup_password').value;
            var confirmPassword = document.getElementById('confirm-password').value;

            if (password !== confirmPassword) {
                document.getElementById('signupFormErrorMessage').innerText = 'Passwords do not match.';
                return;
            }

            const formData = new FormData();
            formData.append("signup_username", username);
            formData.append("signup_password", password);

            // Send the form data to the server using Fetch API
            fetch('/server/signup', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        // Successful signup
                        window.location.href = '/home';
                    } else {
                        // Parse JSON response
                        return response.json().then(data => {
                            // Server returned an error, display the error message
                            document.getElementById('signupFormErrorMessage').innerText = data.message;
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