<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/whisper/topbar-middle/content.php'; ?>
<div id="messageFeedContainer"></div>
<div id="messageInputContainer">
    <div id="messageInputArea">
        <input type="text" id="messageInput" placeholder="Type your message..." onkeydown="handleKeyPress(event)" required>
        <label for="sendMessageBtn" class="btn btn2"><i class="fas fa-chevron-double-right"></i></label>
        <button id="sendMessageBtn" class="hidden" onclick="sendMessage()"></button>
    </div>
</div>
<script src="/scripts/webCrypto.js"></script>
<script>
    const messageFeedContainer = document.getElementById('messageFeedContainer');

    function displayMessage(messages) {
        if (!messageFeedContainer) {
            console.error("message container not found");
            return;
        }
        messageFeedContainer.innerHTML = '';
        messages.forEach(message => {
            const messageElement = document.createElement("div");
            messageElement.textContent = message.message_text;
            messageElement.classList.add(message.type);

            messageFeedContainer.appendChild(messageElement);
        });
    }


    function handleKeyPress(event) {
        if (event.key === "Enter") {
            sendMessage();
        }
    }



    // Function to handle the 'updateNeeded' event
    function handleUpdateEvent(username = '') {
        fetchMessages(username);
        updateTitle(username !== '' ? username : 'Messages');
    }


    document.addEventListener('DOMContentLoaded', () => {

        // Add event listener for 'update' event on messageFeedContainer div
        messageFeedContainer.addEventListener("updateNeeded", handleUpdateEvent);

        // Fetch messages initially
        handleUpdateEvent();


    });
</script>