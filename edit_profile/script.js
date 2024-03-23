document.addEventListener('DOMContentLoaded', function () {
    var image = document.getElementById('croppedImage');
    var cropper;

    document.getElementById('inputImage').addEventListener('change', function (event) {
        var files = event.target.files;
        var reader = new FileReader();
        reader.onload = function () {
            image.src = reader.result;
            if (cropper) {
                cropper.destroy();
            }
            // Initialize cropper after image is loaded
            image.onload = function() {
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 2,
                    autoCropArea: 1,
                });
            };
        };
        reader.readAsDataURL(files[0]);
    });

    document.getElementById('cropButton').addEventListener('click', function () {
        if (!cropper) {
            console.error('Cropper is not initialized.');
            return;
        }
        var canvas = cropper.getCroppedCanvas();
        if (!canvas) {
            return;
        }
        canvas.toBlob(function (blob) {
            var formData = new FormData();
            formData.append('croppedImage', blob);
            formData.append('filename', 'profile_picture.jpg'); // Change the filename as needed
            fetch('save_image.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        });
    });
});
