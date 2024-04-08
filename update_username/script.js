document.addEventListener('DOMContentLoaded', function () {
    const updateUsernameForm = document.getElementById('updateUsernameForm');

    updateUsernameForm.addEventListener('submit', function (event) {
        // Prevent the default form submission
        event.preventDefault();

        // Serialize the form data
        const formData = new FormData(updateUsernameForm);

        // Send the data to the server
        fetch('../update_username/server.php', {
            method: 'POST', // Change method as required
            body: formData
        })
        .then(response => {
            if (response.ok) {
                window.location.href = '../profile/';
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
