<?php
include_once '../functions/userDetails.php';
?>

<div class="createPostContainer">
    <div id="createPostContainerPart1">
        <img src="<?= htmlspecialchars($profilePicture) ?>" class="profile-picture" alt="">
    </div>
    <div id="createPostContainerPart2">
        <textarea id="createPostTextArea" placeholder="What is happening?!" rows="1" oninput="adjustTextAreaHeight(this)"></textarea>
        <div id="createPostMediaPreview"></div>
        <div id="createPostButtons">
            <div>
                <label for="createPostMediaUpload" class="btn" title="Media"><i class="fa-regular fa-image"></i></label>
                <input type="file" id="createPostMediaUpload" accept="image/*, video/*">
            </div>
            <div>
                <button id="createPostPostButton" class="btn">Post</button>
            </div>
        </div>
    </div>
</div>


<script src="../create-post/script.js"></script>
