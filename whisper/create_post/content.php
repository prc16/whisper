<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/php/userDetails.php'; ?>

<div class="createPostContainer">
    <div id="createPostContainerPart1">
        <img src="<?= htmlspecialchars($profilePicture) ?>" class="profile-picture" alt="">
    </div>
    <div id="createPostContainerPart2">
        <textarea id="createPostTextArea" placeholder="What's on your mind?" rows="1" required></textarea>
        <div id="createPostMediaPreview">
            <!-- Placeholder for media preview -->
        </div>
        <div id="createPostErrorMessage" class="errorMessage"></div>
        <div id="createPostButtons">
            <div id="createPostButtonsStart">
                <label for="createPostMediaUpload" class="btn btn2" title="Media"><i class="fas fa-paperclip"></i></label>
                <input type="file" id="createPostMediaUpload" accept="image/jpeg, image/png, image/gif, image/webp">
                <button id="createPostClearButton" class="btn hidden" title="Remove"><i class="fas fa-minus-circle"></i></button>

                <input type="checkbox" id="createPostAnonCheckbox" class="hidden" name="createPostAnonCheckbox">
                <label for="createPostAnonCheckbox" class="btn btn2" title="Anonymous Post"><i class="fas fa-user-shield"></i></label>

                <label id="createPostTimerButtonLabel" for="createPostTimerButton" class="btn btn2" title="Self Destruct"><i class="fas fa-clock"></i></label>
                <button id="createPostTimerButton" class="hidden" title="Self Destruct"></button>

                <!-- Dropdown menu for selecting time interval -->
                <div class="dropdown">
                    <div id="timerDropdown" class="dropdown-content">
                        <a href="#" data-time="0">none</a>
                        <a href="#" data-time="5">5 seconds</a>
                        <a href="#" data-time="3600">1 hour</a>
                        <a href="#" data-time="86400">1 day</a>
                        <a href="#" data-time="2592000">1 month</a>
                        <a href="#" data-time="31536000">1 year</a>
                        <!-- Add more time options as needed -->
                    </div>
                </div>
            </div>
            <div>
                <button id="createPostPostButton" class="btn">Post</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const button = document.getElementById("createPostTimerButton");
        const dropdown = document.getElementById("timerDropdown");
        const createPostTextArea = document.getElementById("createPostTextArea");
        const createPostMediaUpload = document.getElementById("createPostMediaUpload");
        const createPostMediaPreview = document.getElementById("createPostMediaPreview");
        const createPostPostButton = document.getElementById("createPostPostButton");
        const postsFeedContainer = document.getElementById("postsFeedContainer");
        const createPostClearButton = document.getElementById("createPostClearButton");
        const createPostErrorMessage = document.getElementById('createPostErrorMessage');
        const createPostAnonCheckbox = document.getElementById('createPostAnonCheckbox');
        const createPostTimerButtonLabel = document.getElementById('createPostTimerButtonLabel');
        let dropDownValue = 0;

        document.addEventListener('click', function(event) {
            const target = event.target;
            if (target.id === "createPostTimerButton") {
                dropdown.classList.toggle("show");
            } else if (!target.matches(".dropdown-content")) {
                dropdown.classList.remove('show');
            }
        });

        dropdown.addEventListener("click", function(event) {
            const target = event.target;
            if (target.tagName === "A") {
                const selectedTime = target.getAttribute("data-time");
                console.log("Selected time: " + selectedTime + " minutes");
                dropdown.classList.remove("show");
                dropDownValue = parseInt(selectedTime);
                if (dropDownValue > 0) {
                    createPostTimerButtonLabel.classList.add("btn-selected");
                    createPostTimerButtonLabel.setAttribute("title", target.textContent);
                } else {
                    createPostTimerButtonLabel.classList.remove("btn-selected");
                    createPostTimerButtonLabel.setAttribute("title", "Self Destruct");
                }
            }
        });

        createPostTextArea.addEventListener("input", function() {
            this.style.height = "auto";
            this.style.height = this.scrollHeight + "px";
            createPostErrorMessage.innerText = "";
        });

        createPostClearButton.addEventListener('click', function() {
            createPostMediaUpload.value = "";
            createPostMediaPreview.innerHTML = "";
            createPostClearButton.classList.add('hidden');
            createPostErrorMessage.innerText = "";
        });

        createPostMediaUpload.addEventListener("change", function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const mediaType = file.type.split("/")[0];
                    if (mediaType === "image") {
                        createPostErrorMessage.innerText = "";
                        createPostClearButton.classList.remove('hidden');
                        createPostMediaPreview.innerHTML = `<img src="${e.target.result}" alt="Image Preview" class="image-preview">`;
                    } else {
                        createPostClearButton.click(); // Clear inputs and hide button
                        createPostErrorMessage.innerText = "Only images are allowed";
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        createPostPostButton.addEventListener("click", function() {
            const postText = createPostTextArea.value.trim();
            const file = createPostMediaUpload.files[0];

            console.log(dropDownValue);
            const formData = new FormData();
            formData.append("post_text", postText);
            formData.append("media_file", file);
            formData.append("anon_post", createPostAnonCheckbox.checked);
            formData.append("expire_at", dropDownValue);

            fetch('/server/create/post', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    createPostClearButton.click(); // Clear inputs and hide button
                    createPostTextArea.value = "";
                    createPostTextArea.style.height = "auto";
                    createPostAnonCheckbox.checked = false;
                    dropDownValue = 0;
                    createPostTimerButtonLabel.classList.remove("btn-selected");
                    createPostTimerButtonLabel.setAttribute("title", "Self Destruct");
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
        });
    });
</script>