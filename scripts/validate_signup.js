function validateForm() {
    var email = document.getElementById("email").value;
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirmPassword").value;

    // Add your custom validation logic here
    // For example, you can check if the passwords match

    if (password !== confirmPassword) {
        alert("Passwords do not match");
        return false;
    }

    return true;
}
