<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/php/userDetails.php'; ?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/whisper/topbar-middle/content.php'; ?>
<!--------------- profile main --------------->
<div id="profile-main">
    <div id="profile-container-large">
        <img id="profile-container-large-picture" src="<?= htmlspecialchars($profilePicture) ?>">
        <div id="profile-container-large-username" class="profile-username">
            <?= htmlspecialchars($username) ?>
        </div>
    </div>
</div>
<?php if ($loggedIn) : ?>
<div id="followeesFeedContainer"></div>
<script src="/scripts/follow.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {

        // Fetch posts initially
        fetchFollowees();

        // Event listener for voting
        document.addEventListener('click', follow);

        // Fetch posts every 5 seconds
        // setInterval(handleUpdateEvent, 5000);
    });
</script>
<?php endif; ?>