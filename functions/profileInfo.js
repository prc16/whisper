function getProfileInfo(callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../functions/getProfileInfo.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var profileInfo = JSON.parse(xhr.responseText);
                callback(profileInfo); // Pass profileInfo to the callback function
            } else {
                console.error('Error fetching profile info:', xhr.status);
            }
        }
    };
    xhr.send();
}

// Function to set profile
function setProfileInfo(profileInfo) {
    document.getElementById('profile-picture').src = profileInfo.profile_picture;
    document.getElementById('profile-username').textContent = profileInfo.username;
}

// Attach getProfileInfo to window.onload
window.onload = function() {
    getProfileInfo(setProfileInfo);
};
