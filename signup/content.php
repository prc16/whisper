<?php include_once '../topbar-middle/content.php'; ?>
<div id="signup-container">
    <form id="signup-form" method="POST" action="../signup/server.php">
        <div id="signupFormErrorMessage" class="errorMessage"></div>
        <input type="text" id="signup-username" name="signup-username" placeholder="Username" required>
        <input type="password" id="signup-password" name="signup-password" placeholder="Password" required>
        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm Password" required>
        <input type="submit" value="Sign Up" class="btn">
    </form>
</div>

<script>
    document.getElementById('signup-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting

        var username = document.getElementById('signup-username').value;
        var password = document.getElementById('signup-password').value;
        var confirmPassword = document.getElementById('confirm-password').value;

        if (password !== confirmPassword) {
            document.getElementById('signupFormErrorMessage').innerText = 'Passwords do not match.';
            return;
        }

        // Send the form data to the server using AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../signup/server.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Successful signup, redirect to maintenance page
                window.location.href = '../home/';
            } else {
                // Server returned an error, display the error message
                document.getElementById('signupFormErrorMessage').innerText = xhr.responseText;
            }
        };
        xhr.send('signup-username=' + encodeURIComponent(username) + '&signup-password=' + encodeURIComponent(password));
    });
</script>
