<?php include_once '../php/userDetails.php'; ?>

<div class="createPostContainer">
    <div id="createPostContainerPart1">
        <img src="<?= htmlspecialchars($profilePicture) ?>" class="profile-picture" alt="">
    </div>
    <div id="createPostContainerPart2">
        <textarea id="createPostTextArea" placeholder="What is happening?!" rows="1" required></textarea>
        <div id="createPostMediaPreview">
            <!-- Placeholder for media preview -->
        </div>
        <div id="createPostErrorMessage" class="errorMessage"></div>
        <div id="createPostButtons">
            <div id="createPostButtonsStart">
                <label for="createPostMediaUpload" class="btn btn2" title="Media"><i class="fas fa-paperclip"></i></label>
                <input type="file" id="createPostMediaUpload" accept="image/jpeg, image/png, image/gif, image/webp">
                <button id="createPostClearButton" class="btn" style="display: none;"><i class="fas fa-minus-circle"></i></button>
            </div>
            <div>
                <button id="createPostPostButton" class="btn">Post</button>
            </div>
        </div>
    </div>
</div>


<script src="../create_post/script.js"></script>
