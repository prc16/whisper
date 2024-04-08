function updateProfilePicture() {
    // Add functionality to update profile picture
    // window.location.href = '../update_profile/';
    // Trigger event
    let inputImage = document.getElementById('inputImage');
    
    // Check if a file has already been selected
    if (inputImage.files.length === 0) {
        inputImage.click();
    }
    document.getElementById('updateUsernameContainer').style.display = 'none';
    document.getElementById('updateProfileContainer').style.display = 'flex';
}

function updateUsername() {
    // Add functionality to update username
    // window.location.href = '../update_username/';
    document.getElementById('updateProfileContainer').style.display = 'none';
    document.getElementById('updateUsernameContainer').style.display = 'block';
}
