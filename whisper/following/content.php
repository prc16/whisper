<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/php/followees.php'; ?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/whisper/topbar-middle/content.php'; ?>
<!--------------- profile main --------------->
<div id="profile-main">
    <div id="profile-container-large">
        <img id="profile-container-large-picture" src="<?= htmlspecialchars($reqProfilePicture) ?>">
        <div id="profile-container-large-username">
            <?= htmlspecialchars($reqUsername) ?>
        </div>
    </div>
    <div id="profileFollowOptions" class="profileButtonsContainer">
        <button class="btn" id="showFollowersButton">Followers</button>
        <button class="btn" id="showFolloweesButton">Following</button>
    </div>
</div>
<div id="followeeListContainer">
    <?php
    // Output HTML for each followee
    foreach ($followees as $followee) {
        $username = htmlspecialchars($followee['username']);
        $profilePicture = getProfilePicture($conn, $followee['profile_file_id']);
    ?>
        <a href="/u/<?= $username ?>" class="username_link">
            <div class="displayUserContainer">
                <img class="profile-picture" src="<?= $profilePicture ?>">
                <div class="profile-username"><?= $username ?></div>
            </div>
        </a>
    <?php
    }
    ?>
</div>
<div id="followerListContainer" class="hidden">
    <?php
    // Output HTML for each followee
    foreach ($followers as $follower) {
        $username = htmlspecialchars($follower['username']);
        $profilePicture = getProfilePicture($conn, $follower['profile_file_id']);
    ?>
        <a href="/u/<?= $username ?>" class="username_link">
            <div class="displayUserContainer">
                <img class="profile-picture" src="<?= $profilePicture ?>">
                <div class="profile-username"><?= $username ?></div>
            </div>
        </a>
    <?php
    }
    ?>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Get references to the buttons and the divisions
        const showFollowersButton = document.getElementById('showFollowersButton');
        const showFolloweesButton = document.getElementById('showFolloweesButton');
        const followerListContainer = document.getElementById('followerListContainer');
        const followeeListContainer = document.getElementById('followeeListContainer');

        // Add event listeners to the buttons
        showFollowersButton.addEventListener('click', function() {
            // Toggle visibility of follower list
            if (followerListContainer.classList.contains('hidden')) {
                followerListContainer.classList.remove('hidden');
            }
            if (!followeeListContainer.classList.contains('hidden')) {
                followeeListContainer.classList.add('hidden');
            }
        });

        showFolloweesButton.addEventListener('click', function() {
            // Toggle visibility of followee list
            if (!followerListContainer.classList.contains('hidden')) {
                followerListContainer.classList.add('hidden');
            }
            if (followeeListContainer.classList.contains('hidden')) {
                followeeListContainer.classList.remove('hidden');
            }
        });
    });
</script>