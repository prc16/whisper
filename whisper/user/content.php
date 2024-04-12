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
        <div id="profile-update-options" class="profileButtonsContainer">
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
    <?php elseif ((strcasecmp($reqUsername, 'Anonymous') !== 0) && $isFollowing) : ?>
        <div id="profile-update-options" class="profileButtonsContainer">
            <button class="follow-btn btn" data-type='unfollow' data-id='<?= htmlspecialchars($reqUsername) ?>' id="profileFollowButton">Unfollow</button>
        </div>
    <?php elseif (strcasecmp($reqUsername, 'Anonymous') !== 0) : ?>
        <div id="profile-update-options" class="profileButtonsContainer">
            <button class="follow-btn btn" data-type='follow' data-id='<?= htmlspecialchars($reqUsername) ?>' id="profileFollowButton">Follow</button>
        </div>
    <?php endif; ?>
</div>
<!-- Display existing posts -->
<div id="postsFeedContainer"></div>
<script src="/scripts/posts.js"></script>
<script src="/scripts/follow.js"></script>
<script>
    // Function to handle the 'updateNeeded' event
    function handleUpdateEvent() {
        fetchPosts('<?= htmlspecialchars($reqUsername) ?>');
    }

    document.addEventListener('DOMContentLoaded', () => {

        // Add event listener for 'update' event on displayPosts div
        postsFeedContainer.addEventListener("updateNeeded", handleUpdateEvent);

        // Fetch posts initially
        handleUpdateEvent();

        // Event listener for voting
        document.addEventListener('click', vote);
        try {
            document.getElementById('profileFollowButton').addEventListener('click', follow);
        } catch (e) {};

        // Fetch posts every 5 seconds
        // setInterval(handleUpdateEvent, 5000);
    });
</script>