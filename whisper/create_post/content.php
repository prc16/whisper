<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/php/userDetails.php'; ?>

<div class="createPostContainer">
    <div id="createPostContainerPart1">
        <img src="<?= htmlspecialchars($profilePicture) ?>" class="profile-picture" alt="">
    </div>
    <div id="createPostContainerPart2">
        <textarea id="createPostTextArea" placeholder="What is happening?!" rows="1" required></textarea>
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

                <label for="createPostTimerButton" class="btn btn2" title="Self Destruct"><i class="fas fa-clock"></i></label>
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
    // Get the button and dropdown elements
    const button = document.getElementById("createPostTimerButton");
    const dropdown = document.getElementById("timerDropdown");
    var dropDownValue = 0;

    // Toggle dropdown when button is clicked
    button.addEventListener("click", function() {
        dropdown.classList.toggle("show");
    });

    // Close the dropdown if the user clicks outside of it
    window.addEventListener("click", function(event) {
        if (!event.target.matches("#createPostTimerButton")) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    });

    // Set the selected time when a dropdown option is clicked
    dropdown.addEventListener("click", function(event) {
        if (event.target.tagName === "A") {
            var selectedTime = event.target.getAttribute("data-time");
            // Do something with the selected time value, like triggering an action
            console.log("Selected time: " + selectedTime + " minutes");
            // You can close the dropdown here if needed
            dropdown.classList.remove("show");
            dropDownValue = parseInt(selectedTime);
            const timerBtn = document.querySelector('label[for="createPostTimerButton"]');
            if (dropDownValue > 0) {
                timerBtn.classList.add("btn-selected");
                timerBtn.setAttribute("title", event.target.textContent);
            } else {
                timerBtn.classList.remove("btn-selected");
                timerBtn.setAttribute("title", "Self Destruct");
            }
        }
    });
</script>
<script src="/scripts/create_post.js"></script>