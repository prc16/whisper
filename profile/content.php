<?php
include_once '../functions/userDetails.php';
?>
<!--------------- profile main --------------->
<div id="profile-main">
    <div id="profile-container-large">
        <img id="profile-container-large-picture" src="<?= htmlspecialchars($profilePicture) ?>">
        <div id="profile-container-large-username"><?= htmlspecialchars($username) ?></div>
    </div>
    <div id="profile-update-options">
        <?php if ($loggedIn): ?>
            <button class="btn" onclick="updateProfilePicture()">Update Picture</button>
            <button class="btn" onclick="updateUsername()">Update Username</button>
        <?php endif; ?>
    </div>
</div>
