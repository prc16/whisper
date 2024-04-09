document.addEventListener('DOMContentLoaded', () => {
    // DOM elements
    const createPostTextArea = document.getElementById("createPostTextArea");
    const createPostMediaUpload = document.getElementById("createPostMediaUpload");
    const createPostMediaPreview = document.getElementById("createPostMediaPreview");
    const createPostPostButton = document.getElementById("createPostPostButton");
    const postsFeedContainer = document.getElementById("postsFeedContainer");
    const createPostClearButton = document.getElementById("createPostClearButton");
    const createPostErrorMessage = document.getElementById('createPostErrorMessage');

    // Event listeners
    createPostTextArea.addEventListener("input", handleTextAreaInput);
    createPostClearButton.addEventListener('click', handleClearButtonClick);
    createPostMediaUpload.addEventListener("change", handleMediaUpload);
    createPostPostButton.addEventListener("click", handlePostButtonClick);

    // Function to handle resizing of textarea
    function handleTextAreaInput(event) {
        this.style.height = "auto";
        this.style.height = this.scrollHeight + "px";
        createPostErrorMessage.innerText = "";
    }

    // Function to handle clear button click
    function handleClearButtonClick() {
        createPostMediaUpload.value = "";
        createPostMediaPreview.innerHTML = "";
        createPostClearButton.classList.add('hidden');
        createPostErrorMessage.innerText = "";
    }

    // Function to handle file upload
    function handleMediaUpload() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const mediaType = file.type.split("/")[0];
                if (mediaType === "image") {
                    createPostErrorMessage.innerText = "";
                    createPostClearButton.classList.remove('hidden');
                    createPostMediaPreview.innerHTML = `<img src="${e.target.result}" alt="Image Preview" class="image-preview">`;
                } else {
                    handleClearButtonClick(); // Clear inputs and hide button
                    createPostErrorMessage.innerText = "Only images are allowed";
                }
            };
            reader.readAsDataURL(file);
        }
    }

    // Function to handle post submission
    function handlePostButtonClick() {
        const postText = createPostTextArea.value.trim();
        const file = createPostMediaUpload.files[0];

        if (postText === "" && !file) {
            createPostErrorMessage.innerText = "Empty post not allowed.";
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
                    handleClearButtonClick(); // Clear inputs and hide button
                    createPostTextArea.value = "";
                    createPostTextArea.style.height = "auto";
                    postsFeedContainer.dispatchEvent(new Event('updateNeeded'));
                } else {
                    return response.json().then(data => {
                        createPostErrorMessage.innerText = data.message;
                        console.log(data.message);
                    });
                }
            })
            .catch(error => {
                console.error('There was a problem with your fetch operation:', error);
            });
    }
});
