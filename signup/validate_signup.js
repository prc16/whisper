function validateForm() {

    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirmPassword").value;

    // Check if the passwords match
    if (password !== confirmPassword) {
        alert("Passwords do not match");
        return false;
    }

    return true;
}
