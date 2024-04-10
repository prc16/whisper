<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/php/userDetails.php'; ?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/whisper/topbar-middle/content.php'; ?>
<!--------------- profile main --------------->
<div id="profile-main">
    <div id="profile-container-large">
        <img id="profile-container-large-picture" src="<?= htmlspecialchars($profilePicture) ?>">
        <div id="profile-container-large-username">
            <?= htmlspecialchars($username) ?>
        </div>
    </div>
    <?php if ($loggedIn) : ?>
        <div id="profile-update-options">
            <button class="btn" onclick="updateProfilePicture()">Update Picture</button>
            <button class="btn" onclick="updateUsername()">Update Username</button>
        </div>
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/whisper/update_profile/content.php'; ?>
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/whisper/update_username/content.php'; ?>
    <?php endif; ?>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
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
    });
</script>