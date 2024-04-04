document.addEventListener('DOMContentLoaded', () => {
    const createPostTextArea = document.getElementById("createPostTextArea");
    const createPostMediaUpload = document.getElementById("createPostMediaUpload");
    const createPostMediaPreview = document.getElementById("createPostMediaPreview");
    const createPostPostButton = document.getElementById("createPostPostButton");

    createPostTextArea.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            this.rows += 1; // Increase the number of rows when Enter key is pressed
        }
        this.style.height = this.scrollHeight + "px";
    });

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
            }
            reader.readAsDataURL(file);
        }
    });

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
                    alert("Post uploaded successfully.");
                    createPostTextArea.value = "";
                    createPostMediaUpload.value = "";
                    createPostMediaPreview.innerHTML = "";
                    createPostTextArea.rows = 1;
                } else {
                    alert("Failed to upload post. Please try again later.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred. Please try again later.");
            });
    });

    //createPostTextArea.focus();
});