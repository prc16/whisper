window.onload = function() {
    loadProfileInfo();
};

function loadProfileInfo() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../profile/server.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var profileInfo = JSON.parse(xhr.responseText);
                document.getElementById('profile-picture').src = profileInfo.profile_picture;
                document.getElementById('username').textContent = profileInfo.username;
            } else {
                console.error('Error fetching profile info:', xhr.status);
            }
        }
    };
    xhr.send();
}

function editProfilePicture() {
    // Add functionality to edit profile picture
    window.location.href = '../edit_profile/';
}

function editUsername() {
    // Add functionality to edit username
    window.location.href = '../edit_username/';
}
