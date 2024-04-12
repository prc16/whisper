<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/php/userDetails.php'; ?>
<div class="sidebar-container">
    <nav class="imp-links">
        <a href="/"><i class="fab fa-discourse"></i><b>Whisper</b></a>
        <a href="/home"><i class="far fa-home-alt"></i>Home</a>
        <a href="/u/<?= htmlspecialchars($username) ?>"><i class="far fa-user"></i>Profile</a>
        <a href="#"><i class="far fa-envelope"></i>Messages</a>
        <a href="/following"><i class="far fa-user-friends"></i>Following</a>
        <?php if ($loggedIn) : ?>
            <a href="/logout"><i class="far fa-sign-out"></i>Log Out</a>
        <?php else : ?>
            <a href="/login"><i class="far fa-sign-in-alt"></i>Log In</a>
            <a href="/signup"><i class="far fa-user-plus"></i>Sign Up</a>
        <?php endif; ?>
    </nav>
    <div class="bottom-left">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/whisper/bottom-left/content.php"; ?>
    </div>
</div>
<script src="/scripts/activeNavLinks.js"></script>