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

<script src="/dependencies/cropperjs/cropper.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('inputImage');
        const image = document.getElementById('cropper_image');
        const cropButton = document.getElementById('cropButton');

        document.getElementById('uploadButton').addEventListener('click', function() {
            // Check if a file has already been selected and Cropper is not initialized
            if (input.files.length === 0 && !cropper) {
                input.click();
            }
        });

        let cropper;

        const cropperOptions = {
            aspectRatio: 1,
            viewMode: 2,
            autoCropArea: 1,
        };

        input.addEventListener('change', handleInputChange);

        function handleInputChange(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = () => {
                image.src = reader.result;
                if (!cropper) {
                    cropper = new Cropper(image, cropperOptions);
                } else {
                    cropper.replace(reader.result);
                }
            };
            reader.readAsDataURL(file);
        }

        cropButton.addEventListener('click', handleCropButtonClick);

        function handleCropButtonClick() {
            if (!cropper) return;

            const canvas = cropper.getCroppedCanvas({
                width: 400,
                height: 400,
                fillColor: '#fff',
                imageSmoothingEnabled: false,
                imageSmoothingQuality: 'high',
            });

            canvas.toBlob(uploadImage, 'image/jpeg', 0.8);
        }

        function uploadImage(blob) {
            const formData = new FormData();
            formData.append('profile_picture', blob, 'profile_picture.jpg');

            fetch('/server/update/profile', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        // Redirect to the Profile page
                        window.location.href = '/profile';
                    } else {
                        // Parse JSON response
                        return response.json().then(data => {
                            document.getElementById('updateProfileErrorMessage').innerText = data.message;
                            console.log(data.message);
                        });
                    }
                })
                .catch(error => {
                    console.error('There was a problem with your fetch operation:', error);
                });
        }
    });
</script>