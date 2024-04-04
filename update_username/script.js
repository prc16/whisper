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
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Handle response from server
            console.log(data);
            alert(data.message);
            if (data.success) {
                window.location.href = '../profile/';
            }
        })
        .catch(error => {
            // Handle error
            console.error('There was a problem with your fetch operation:', error);
        });
    });
});
