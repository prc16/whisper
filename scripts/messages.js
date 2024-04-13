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


function fetchMessages(username = '') {
    fetch('/server/messages/' + username)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(messages => {
            displayMessage(messages);
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });
}

function handleKeyPress(event) {
    if (event.key === "Enter") {
        sendMessage();
    }
}