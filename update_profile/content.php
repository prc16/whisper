<div id="updateProfileContainer">
    <div id="updateProfileErrorMessage" class="errorMessage">
        <!-- Placeholder for Error Messages -->
    </div>
    <div id="cropper-container">
        <img id="cropper_image" src="" alt="">
    </div>
    <div id="updateProfileContainerButtons">
        <div>
            <label for="inputImage" class="btn btn2" title="Image" id="uploadButton"><i class="far fa-image"></i></label>
            <input type="file" id="inputImage" accept="image/*" class="btn">
        </div>

        <div>
            <button id="cropButton" class="btn">Done</button>
        </div>
    </div>
</div>

<script src="../dependencies/cropperjs/cropper.min.js"></script>
<script src="../update_profile/script.js"> </script>