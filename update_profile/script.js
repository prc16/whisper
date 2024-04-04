document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('inputImage');
    const image = document.getElementById('cropper_image');
    const cropperContainer = document.getElementById('cropper-container');
    const cropButton = document.getElementById('cropButton');
    
    const cropperOptions = {
        aspectRatio: 1,
        viewMode: 2,
        autoCropArea: 1,
    };

    let cropper;

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
            cropperContainer.style.display = 'block';
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

        fetch('server.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            alert(data.message);
            window.location.href = '../profile/';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error uploading image.');
        });
    }
});
