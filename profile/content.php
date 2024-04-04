<?php
include_once '../functions/userDetails.php';
?>
<!--------------- profile main --------------->
<div id="profile-main">
    <div class="profile-container-large">
        <img id="profile-container-large-picture" src="<?= htmlspecialchars($profilePicture) ?>">
        <div id="profile-container-large-username"><?= htmlspecialchars($username) ?></div>
    </div>
    <div id="profile-update-options">
        <?php if ($loggedIn): ?>
            <button onclick="updateProfilePicture()">Update Picture</button>
            <button onclick="updateUsername()">Update Username</button>
        <?php endif; ?>
    </div>
</div>
