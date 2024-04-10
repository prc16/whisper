<div id="updateUsernameContainer">
    <form id="updateUsernameForm">
        <label for="newUsername">
            New Username:
        </label>
        <div id="updateUsernameErrorMessage" class="errorMessage">
            <!-- Placeholder for Error Messages -->
        </div>
        <input type="text" id="newUsername" name="newUsername" required>
        <button type="submit" class="btn">Submit</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const updateUsernameForm = document.getElementById('updateUsernameForm');

        updateUsernameForm.addEventListener('submit', function(event) {
            // Prevent the default form submission
            event.preventDefault();

            // Serialize the form data
            const formData = new FormData(updateUsernameForm);

            // Send the data to the server
            fetch('/whisper/update_username/server.php', {
                    method: 'POST', // Change method as required
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        window.location.href = '/profile';
                    } else {
                        // Parse JSON response
                        return response.json().then(data => {
                            document.getElementById('updateUsernameErrorMessage').innerText = data.message;
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