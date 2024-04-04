<?php
include_once '../functions/userDetails.php';
?>
<!--------------- profile main --------------->
<div id="profile-main">
    <div class="profile-container-large">
        <img id="profile-container-large-picture" src="<?= htmlspecialchars($profilePicture) ?>">
        <div id="profile-container-large-username"><?= htmlspecialchars($username) ?></div>
    </div>
    <div id="profile-edit-options">
        <?php if ($loggedIn): ?>
            <button onclick="editProfilePicture()">Edit Picture</button>
            <button onclick="editUsername()">Edit Username</button>
        <?php endif; ?>
    </div>
</div>
