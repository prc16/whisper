<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/php/userDetails.php'; ?>
<div id="bottom-left-container">
    <a href="/u/<?= htmlspecialchars($username) ?>" class="username_link">
        <div id="bottom-left-profile-container">
            <img id="bottom-left-profile-picture" class="profile-picture" src="<?= htmlspecialchars($profilePicture) ?>">
            <div id="bottom-left-profile-username" class="profile-username"><?= htmlspecialchars($username) ?></div>
        </div>
    </a>
</div>