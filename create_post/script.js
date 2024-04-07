document.addEventListener('DOMContentLoaded', () => {
    const createPostTextArea = document.getElementById("createPostTextArea");
    const createPostMediaUpload = document.getElementById("createPostMediaUpload");
    const createPostMediaPreview = document.getElementById("createPostMediaPreview");
    const createPostPostButton = document.getElementById("createPostPostButton");
    const postsFeedContainer = document.getElementById("postsFeedContainer");
    const createPostButtonsStart = document.getElementById("createPostButtonsStart");

    // Function to handle resizing of textarea
    createPostTextArea.addEventListener("input", function (event) {
        this.style.height = "auto";
        this.style.height = this.scrollHeight + "px";
    });

    // Function to handle file upload
    createPostMediaUpload.addEventListener("change", function () {
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
                
                // Add remove button
                createPostButtonsStart.insertAdjacentHTML('beforeend', '<button id="removeMediaButton" class="btn btn-danger">Remove</button>');
                
                // Add event listener to remove button
                const removeMediaButton = document.getElementById('removeMediaButton');
                removeMediaButton.addEventListener('click', function() {
                    createPostMediaPreview.innerHTML = ''; // Clear media preview
                    createPostMediaUpload.value = ''; // Clear file input
                });
            };
            reader.readAsDataURL(file);
        }
    });

    // Function to handle post submission
    createPostPostButton.addEventListener("click", function () {
        const postText = createPostTextArea.value;
        const file = createPostMediaUpload.files[0];

        if (postText.trim() === "") {
            alert("Please enter some text for your post.");
            return;
        }

        const formData = new FormData();
        formData.append("post_text", postText);
        formData.append("media_file", file);

        fetch('../create_post/server.php', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (response.ok) {
                    createPostTextArea.value = "";
                    createPostMediaUpload.value = "";
                    createPostMediaPreview.innerHTML = "";
                    createPostTextArea.rows = 1;

                    // Trigger update event on displayPosts div
                    const updateEvent = new Event('updateNeeded');
                    postsFeedContainer.dispatchEvent(updateEvent);
                } else {
                    // Parse JSON response
                    return response.json().then(data => {
                        alert(data.message);
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
