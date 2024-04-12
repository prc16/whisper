<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/php/userDetails.php'; ?>

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
                <button id="createPostClearButton" class="btn hidden" title="Remove"><i class="fas fa-minus-circle"></i></button>
                
                <input type="checkbox" id="createPostAnonCheckbox" class="hidden" name="createPostAnonCheckbox">
                <label for="createPostAnonCheckbox" class="btn btn2" title="Anonymous Post"><i class="fas fa-user-shield"></i></label>
                
            </div>
            <div>
                <button id="createPostPostButton" class="btn">Post</button>
            </div>
        </div>
    </div>
</div>
<script src="/scripts/create_post.js"></script>