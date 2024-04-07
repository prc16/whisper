document.addEventListener('DOMContentLoaded', () => {
    const createPostTextArea = document.getElementById("createPostTextArea");
    const createPostMediaUpload = document.getElementById("createPostMediaUpload");
    const createPostMediaPreview = document.getElementById("createPostMediaPreview");
    const createPostPostButton = document.getElementById("createPostPostButton");
    const postsFeedContainer = document.getElementById("postsFeedContainer");
    const createPostButtonsStart = document.getElementById("createPostButtonsStart");
    const createPostClearButton = document.getElementById("createPostClearButton");

    // Function to handle resizing of textarea
    createPostTextArea.addEventListener("input", function (event) {
        this.style.height = "auto";
        this.style.height = this.scrollHeight + "px";
        // display remove button
        createPostClearButton.style.display = 'block';
    });

    createPostClearButton.addEventListener('click', function () {
        createPostTextArea.value = "";
        createPostMediaUpload.value = "";
        createPostMediaPreview.innerHTML = "";
        createPostTextArea.rows = 1;
        createPostTextArea.style.height = "auto";
        createPostClearButton.style.display = 'none';
    });

    // Function to handle file upload
    createPostMediaUpload.addEventListener("change", function () {
        // display remove button
        createPostClearButton.style.display = 'block';
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const mediaType = file.type.split("/")[0];
                if (mediaType === "image") {
                    createPostMediaPreview.innerHTML = `<img src="${e.target.result}" alt="Media Preview" class="image-preview">`;
                } else if (mediaType === "video") {
                    createPostMediaPreview.innerHTML = `<video src="${e.target.result}" alt="Media Preview" class="video-preview" controls></video>`;
                }

            };
            reader.readAsDataURL(file);
        }
    });

    // Function to handle post submission
    createPostPostButton.addEventListener("click", function () {
        const postText = createPostTextArea.value;
        const file = createPostMediaUpload.files[0];

        if (postText.trim() === "") {
            document.getElementById('createPostErrorMessage').innerText = "Please enter some text for your post.";
            return;
        }

        const formData = new FormData();
        formData.append("post_text", postText.trim());
        formData.append("media_file", file);

        fetch('../create_post/server.php', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (response.ok) {
                    const clearEvent = new Event('click');
                    createPostClearButton.dispatchEvent(clearEvent);

                    // Trigger update event on displayPosts div
                    const updateEvent = new Event('updateNeeded');
                    postsFeedContainer.dispatchEvent(updateEvent);
                } else {
                    // Parse JSON response
                    return response.json().then(data => {
                        // Server returned an error, display the error message
                        document.getElementById('createPostErrorMessage').innerText = data.message;
                        console.log(data.message);
                    });
                }
            })
            .catch(error => {
                console.error('There was a problem with your fetch operation:', error);
            });
    });

    //createPostTextArea.focus();
});
