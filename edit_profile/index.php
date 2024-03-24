<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Picture Cropper</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../edit_profile/style.css">
    <link rel="icon" type="image/gif" href="../images/whisper-logo.png">
</head>

<body>

    <div class="container">
        <h1>Edit Profile Picture</h1>
        <div>
            <input type="file" id="inputImage" accept="image/*">
        </div>
        <div id="cropper-container">
            <img id="cropper-image" src="" alt="Image to crop">
        </div>
        <div>
            <button id="cropButton">Crop Image</button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script src="../edit_profile/script.js"> </script>

</body>

</html>