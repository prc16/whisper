window.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('inputImage');
    const image = document.getElementById('cropper-image');
    const cropper = new Cropper(image, {
        aspectRatio: 1,
        viewMode: 2,
        autoCropArea: 1,
    });

    input.addEventListener('change', (e) => {
        const file = e.target.files[0];
        const reader = new FileReader();

        reader.onload = () => {
            image.src = reader.result;
            cropper.replace(reader.result);
        };

        reader.readAsDataURL(file);
    });

    document.getElementById('cropButton').addEventListener('click', () => {
        const canvas = cropper.getCroppedCanvas({
            width: 400, // Set desired width of the resulting image
            height: 400, // Set desired height of the resulting image
            fillColor: '#fff', // Fill color when the result image does not cover the entire cropped area
            imageSmoothingEnabled: false, // Disable image smoothing to retain sharpness
            imageSmoothingQuality: 'high', // Set image smoothing quality
        });

        canvas.toBlob((blob) => {
            const formData = new FormData();
            formData.append('profile_picture', blob, 'profile_picture.jpg');

            fetch('upload.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    alert(data.message);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error uploading image.');
                });
        }, 'image/jpeg', 0.8); // Specify image/jpeg and quality (0.8) for JPEG format
    });

});