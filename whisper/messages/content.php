<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/whisper/topbar-middle/content.php'; ?>
<div id="messageFeedContainer"></div>
<div id="messageInputContainer">
    <div id="messageInputArea">
        <input type="text" id="messageInput" placeholder="Type your message..." onkeydown="handleKeyPress(event)" required>
        <label for="sendMessageBtn" class="btn btn2"><i class="fas fa-chevron-double-right"></i></label>
        <button id="sendMessageBtn" class="hidden" onclick="sendMessage()"></button>
    </div>
</div>
<script src="/scripts/messages.js"></script>
<script>
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