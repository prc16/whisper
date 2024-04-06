<div id="signup-container">
    <div id="signup-topbar-container">
        <h2>Sign Up</h2>
    </div>
    <form id="signup-form" method="POST" action="../signup/server.php">
        <div id="error-message" class="error"></div>
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
            document.getElementById('error-message').innerText = 'Passwords do not match.';
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
            } else if (xhr.status === 400) {
                // Invalid username format, show error message
                document.getElementById('error-message').innerText = 'Invalid username. Username can only contain letters, numbers, and underscores.';
            } else if (xhr.status === 409) {
                // Username already exists, show error message
                document.getElementById('error-message').innerText = 'Username already exists. Please choose a different username.';
            } else if (xhr.status === 500) {
                // Internal server error, show error message
                document.getElementById('error-message').innerText = 'Error: Unable to create account. Please try again later.';
            }
        };
        xhr.send('signup-username=' + encodeURIComponent(username) + '&signup-password=' + encodeURIComponent(password));
    });
</script>