// Function to handle AJAX requests
function makeRequest(method, url, data, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.setRequestHeader('Content-Type', 'application/json; charset=utf-8');
    xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
            callback(xhr.status, xhr.responseText);
        }
    };
    xhr.send(JSON.stringify(data));
}

// Function to handle form submission
document.getElementById('editForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission
    const newUsername = document.getElementById('newUsername').value;
    const data = { username: newUsername }; // Assuming server expects JSON data with a 'username' field
    makeRequest('POST', '../edit_username/server.php', data, (status, responseText) => {
        if (status === 200) {
            // Request successful, handle response accordingly
            alert('Username updated successfully.');
            window.location.href = '../profile/';
        } else {
            // Request failed, handle error
            alert('Failed to update username. Please try again later.');
        }
    });
});