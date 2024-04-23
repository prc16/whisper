<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/whisper/topbar-middle/content.php'; ?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/php/userDetails.php'; ?>
<div id="messageFeedContainer"></div>
<div id="messageInputContainer">
    <div id="messageInputArea">
        <input type="text" id="messageInput" placeholder="Type your message..." onkeydown="handleKeyPress(event)" required>
        <button id="sendMessageBtn" class="btn btn2" onclick="sendMessage()"><i class="fas fa-chevron-double-right"></i></button>
    </div>
</div>

<script src="/scripts/webCrypto.js"></script>
<script>
    const messageFeedContainer = document.getElementById('messageFeedContainer');
    let globUsername = '';

    async function displayMessage(message, type) {
        const messageElement = document.createElement("div");
        messageElement.textContent = message;
        messageElement.classList.add(type);

        messageFeedContainer.prepend(messageElement);
    }

    async function fetchPublicKeyJwk(username) {
        try {
            const response = await fetch('/server/publicKeyJwk/' + username);
            if (!response.ok) {
                throw new Error('Failed to fetch public key');
            }
            const {
                publicKeyJwk
            } = await response.json();
            return JSON.parse(publicKeyJwk);
        } catch (error) {
            console.error('Error fetching public key:', error);
            throw error;
        }
    }

    async function sendEncryptedMessageToServer(encryptedData, initializationVector, username) {
        try {
            const response = await fetch('/server/post/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    encryptedData,
                    initializationVector,
                    username
                })
            });
            if (!response.ok) {
                throw new Error('Failed to store encrypted message');
            }
            console.log('Encrypted message sent successfully.');
        } catch (error) {
            console.error('Error sending encrypted message:', error);
            throw error;
        }
    }

    const displayedMessages = new Set();

    async function receiveAndDecryptMessage(username, privateKeyJwk) {
        try {
            const response = await fetch('/server/messages/' + username);
            if (!response.ok) {
                throw new Error('Failed to retrieve encrypted messages');
            }
            const messages = await response.json();
            const receivedPublicKeyJwk = await fetchPublicKeyJwk(username);
            const derivedKey = await deriveEncryptionKey(receivedPublicKeyJwk, privateKeyJwk);

            messages.forEach(async message => {
                if (!displayedMessages.has(message.id)) {
                    const decryptedMessage = await decryptText(message.encryptedData, message.initializationVector, derivedKey);
                    displayMessage(decryptedMessage, message.type);
                    displayedMessages.add(message.id);
                }
            });
        } catch (error) {
            console.error("Error:", error);
        }
    }

    async function retrieveKeyPairFromIndexedDB() {
        try {
            const db = await idb.openDB('whisperDB', 1, {
                upgrade(db) {
                    db.createObjectStore('keyPairs');
                }
            });
            const keyPair = await db.get('keyPairs', '<?= htmlspecialchars($keyPairId) ?>');
            if (!keyPair) {
                console.error('Key pair not found in IndexedDB.');
            }
            return keyPair;
        } catch (error) {
            console.error('Error retrieving key pair from IndexedDB:', error);
            return null;
        }
    }

    async function encryptAndSendMessage() {
        try {
            const message = document.getElementById('messageInput').value.trim();
            if (message === '' || document.title === 'Messages') {
                return;
            }
            const {
                publicKeyJwk,
                privateKeyJwk
            } = await retrieveKeyPairFromIndexedDB();
            const receivedPublicKeyJwk = await fetchPublicKeyJwk(document.title);
            const encryptionKey = await deriveEncryptionKey(receivedPublicKeyJwk, privateKeyJwk);
            const {
                base64Data,
                base64IV
            } = await encryptText(message, encryptionKey);
            await sendEncryptedMessageToServer(base64Data, base64IV, document.title);
            await receiveAndDecryptMessage(document.title, privateKeyJwk);
            document.getElementById('messageInput').value = '';
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            sendMessage();
        }
    }

    function sendMessage() {
        encryptAndSendMessage();
    }

    async function handleUpdateEvent(username = '') {
        messageFeedContainer.innerHTML = '';
        const keyPair = await retrieveKeyPairFromIndexedDB();
        if (!keyPair) {
            console.error('ERROR: Key pair not found in IndexedDB.');
            await importKeyPrompt();
            return;
        }
        if (username === '' || username === 'Messages') {
            return;
        }
        displayedMessages.clear();
        updateTitle(username);
        globUsername = username;
        const {
            publicKeyJwk,
            privateKeyJwk
        } = keyPair;
        displayMessage("Your messages are End-to-End Encrypted.", 'system');
        fetchMessagesPeriodically(username, privateKeyJwk);
    }

    let fetchMessagesIntervalId;

    async function fetchMessagesPeriodically(username, privateKeyJwk) {
        if (fetchMessagesIntervalId) {
            clearInterval(fetchMessagesIntervalId);
        }
        fetchMessagesIntervalId = setInterval(() => {
            receiveAndDecryptMessage(username, privateKeyJwk);
        }, 2000); // Fetch messages every 2 seconds
    }

    async function importKeyPrompt() {
        const messageElement = document.createElement("div");
        messageElement.innerHTML = `
                <br><p><b>Your Message Box is Locked.</b><br><br><br>
                We could not find your encryption keys in this browser.<br>
                To unlock your message box, got settings and import your keys.</p><br><br>`;
        messageElement.classList.add('system');

        messageFeedContainer.prepend(messageElement);
    }

    document.addEventListener('DOMContentLoaded', async () => {
        messageFeedContainer.addEventListener("updateNeeded", handleUpdateEvent);
        handleUpdateEvent('<?= htmlspecialchars($_SESSION['reqUsername'] ?? '') ?>');
    });
</script>