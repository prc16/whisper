<?php
include_once '../functions/userDetails.php';
?>
<div id="bottom-left-container">
    <div id="bottom-left-profile-container">
        <img id="bottom-left-profile-picture" class="profile-picture" src="<?= htmlspecialchars($profilePicture) ?>">
        <div id="bottom-left-profile-username" class="profile-username"><?= htmlspecialchars($username) ?></div>
    </div>
    <div id="bottom-left-buttons-container">
        <?php if ($loggedIn): ?>
            <a id="bottom-left-buttons-logout" class="logout-btn" href="../logout/logout.php">Logout</a>
        <?php else: ?>
            <a id="bottom-left-buttons-login" class="login-btn" href="../login/">Login</a>
            <a id="bottom-left-buttons-signup" class="signup-btn" href="../signup/">Signup</a>
        <?php endif; ?>
    </div>
</div>
