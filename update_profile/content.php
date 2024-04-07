<?php include_once '../topbar-middle/content.php'; ?>
<div id="updateProfileContainer">
    <div id="cropper-container">
        <img id="cropper_image" src="" alt="Image to crop">
    </div>
    <div id="updateProfileContainerButtons">
        <div>
            <label for="inputImage" class="btn btn2" title="Image"><i class="far fa-image"></i>Upload Image</label>
            <input type="file" id="inputImage" accept="image/*" class="btn">
        </div>
        <div>
            <button id="cropButton" class="btn">Done</button>
        </div>
    </div>
</div>

<script src="../dependencies/cropperjs/cropper.min.js"></script>
<script src="../update_profile/script.js"> </script>