
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/whisper/topbar-middle/content.php'; ?>
<!--------------- profile main --------------->
<div id="profile-main">
    <div id="profile-container-large">
        <img id="profile-container-large-picture" src="<?= htmlspecialchars($reqProfilePicture) ?>">
        <div id="profile-container-large-username">
            <?= htmlspecialchars($reqUsername) ?>
        </div>
    </div>
    <?php if ($reqLoggedIn) : ?>
        <div id="profile-update-options">
            <button class="btn" id="updateProfileButton">Update Picture</button>
            <button class="btn" id="updateUsernameButton">Update Username</button>
        </div>
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/whisper/update_profile/content.php'; ?>
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/whisper/update_username/content.php'; ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                document.getElementById('updateProfileButton').addEventListener("click", updateProfilePicture);
                document.getElementById('updateUsernameButton').addEventListener("click", updateUsername);

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
    <?php endif; ?>
</div>