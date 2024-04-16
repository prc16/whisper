<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/whisper/topbar-middle/content.php'; ?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/php/userDetails.php'; ?>
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
    let globUsername = '';

    async function displayMessage(message, type) {
        return new Promise(resolve => {
            if (!messageFeedContainer) {
                console.error("message container not found");
                resolve();
                return;
            }

            const messageElement = document.createElement("div");
            messageElement.textContent = message;
            messageElement.classList.add(type);

            // Inserting the new message at the beginning
            if (messageFeedContainer.firstChild) {
                messageFeedContainer.insertBefore(messageElement, messageFeedContainer.firstChild);
            } else {
                messageFeedContainer.appendChild(messageElement);
            }

            resolve();
        });
    }

    async function fetchPublicKeyJwk(username) {
        try {
            const response = await fetch('/server/publicKeyJwk/' + username);

            if (!response.ok) {
                throw new Error('Failed to fetch public key');
            }

            const data = await response.json();
            const publicKeyJwk = data.publicKeyJwk;

            // Convert publicKeyJwk string to object
            const publicKeyObject = JSON.parse(publicKeyJwk);

            // Now you can use the publicKeyObject as needed
            console.log('Public Key (JWK) received:', publicKeyObject);

            // You may want to return the publicKeyObject to use it elsewhere in your application
            return publicKeyObject;
        } catch (error) {
            console.error('Error fetching public key:', error);
            // Handle errors as needed
        }
    }

    async function sendEncryptedMessageToServer(encryptedData, initializationVector, username) {
        try {
            // Send the encrypted message to the server
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
            throw error; // Rethrow the error for handling in the calling function
        }
    }

    // Define a set to store IDs or timestamps of displayed messages
    const displayedMessages = new Set();

    async function receiveAndDecryptMessage(username, privateKeyJwk) {
        try {
            // Fetch the encrypted messages from the server
            const response = await fetch('/server/messages/' + username);

            if (!response.ok) {
                throw new Error('Failed to retrieve encrypted messages');
            }

            const messages = await response.json();
            const receivedPublicKeyJwk = await fetchPublicKeyJwk(username);
            const derivedKey = await deriveEncryptionKey(receivedPublicKeyJwk, privateKeyJwk);

            // Decrypt and display each message asynchronously
            for (const message of messages) {
                // Check if the message has already been displayed
                if (!displayedMessages.has(message.id)) {
                    const encryptedData = message.encryptedData;
                    const initializationVector = message.initializationVector;

                    // Decrypt the message
                    const decryptedMessage = await decryptText(encryptedData, initializationVector, derivedKey);

                    // Display the message
                    displayMessage(decryptedMessage, message.type);

                    // Add the message ID or timestamp to the set of displayed messages
                    displayedMessages.add(message.id);
                }
            }
        } catch (error) {
            console.error("Error:", error);
            // Handle errors as needed
        }
    }

    async function retrieveKeyPairFromIndexedDB() {
        try {
            // Open a connection to IndexedDB
            const db = await idb.openDB('whisperDB', 1, {
                upgrade(db) {
                    db.createObjectStore('keyPairs');
                },
            });

            // Get the key pair from the database
            const keyPair = await db.get('keyPairs', '<?= htmlspecialchars($keyPairId) ?>');

            if (keyPair) {
                console.log('Key pair retrieved from IndexedDB:', keyPair);
                return keyPair;
            } else {
                console.error('Key pair not found in IndexedDB.');
                return null;
            }
        } catch (error) {
            console.error('Error retrieving key pair from IndexedDB:', error);
            return null;
        }
    }

    async function importKeyPair(file) {
        if (!file) {
            alert('Please select a file.');
            return;
        }

        try {
            const keyPairData = await readFileAsJSON(file);
            console.log('Imported key pair:', keyPairData);
            await storeKeyPairInIndexedDB(keyPairData.keyPair, keyPairData.keyPairId);
            // Now you can use the key pair data as needed
            // For example, you can reconstruct CryptoKey objects from the JWK data
        } catch (error) {
            console.error('Error importing key pair:', error);
        }
    }

    function readFileAsJSON(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => {
                try {
                    const keyPairData = JSON.parse(reader.result);
                    resolve(keyPairData);
                } catch (error) {
                    reject(error);
                }
            };
            reader.onerror = reject;
            reader.readAsText(file);
        });
    }



    async function encryptAndSendMessage() {
        try {
            const message = document.getElementById('messageInput').value;
            if (message.trim() === '' || document.title === 'Messages') {
                return;
            }
            const {
                publicKeyJwk,
                privateKeyJwk
            } = await retrieveKeyPairFromIndexedDB();
            console.log("Public Key (JWK):", publicKeyJwk);
            console.log("Private Key (JWK):", privateKeyJwk);

            // await sendPublicKey(publicKeyJwk);
            const receivedPublicKeyJwk = await fetchPublicKeyJwk(document.title);

            const encryptionKey = await deriveEncryptionKey(receivedPublicKeyJwk, privateKeyJwk);
            console.log('Derived encryption key:', encryptionKey);

            const {
                base64Data,
                base64IV
            } = await encryptText(message, encryptionKey);
            console.log('Encrypted message:', base64Data);

            await sendEncryptedMessageToServer(base64Data, base64IV, document.title);
            await receiveAndDecryptMessage(document.title, privateKeyJwk);

            // Optionally display a success message
            console.log('Message sent successfully.');
            document.getElementById('messageInput').value = '';
        } catch (error) {
            console.error('Error:', error);
            // Handle errors as needed
        }
    }

    // Function to handle key press event
    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            sendMessage();
        }
    }

    // Function to handle send message button click
    function sendMessage() {
        encryptAndSendMessage();
    }


    // Function to handle the 'updateNeeded' event
    async function handleUpdateEvent(username = '') {
        messageFeedContainer.innerHTML = '';
        keyPair = await retrieveKeyPairFromIndexedDB();
        if (!keyPair) {
            console.error('ERROR: Key pair not found in IndexedDB.');
            await importKeyPrompt();
            return;
        }
        if (username == '') {
            username = globUsername;
        }
        if (username == 'Messages' || username == '') {
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
        // Call fetchMessagesPeriodically to start fetching messages
        fetchMessagesPeriodically(username, privateKeyJwk);
    }

    let fetchMessagesIntervalId;

    // Function to fetch messages every 2 seconds
    async function fetchMessagesPeriodically(username, privateKeyJwk) {
        // Clear previous interval if it exists
        if (fetchMessagesIntervalId) {
            clearInterval(fetchMessagesIntervalId);
        }

        // Set up a new interval
        fetchMessagesIntervalId = setInterval(async () => {
            receiveAndDecryptMessage(username, privateKeyJwk);
        }, 2000); // Fetch messages every 2 seconds (2000 milliseconds)
    }

    async function importKeyPrompt() {
        if (!messageFeedContainer) {
            console.error("message container not found");
            resolve();
            return;
        }

        const messageElement = document.createElement("div");
        messageElement.innerHTML = `
                <p>Your Message Box is Locked.<br>
                Import Your Keys to unlock them.</p>
                <center>
                <label for="messageMediaUpload" class="btn btn2 btn3" title="Media"><i class="far fa-file-import"></i></label>
                <input type="file" id="messageMediaUpload" accept=".json">
                </center>`;
        messageElement.classList.add('system');

        // Inserting the new message at the beginning
        if (messageFeedContainer.firstChild) {
            messageFeedContainer.insertBefore(messageElement, messageFeedContainer.firstChild);
        } else {
            messageFeedContainer.appendChild(messageElement);
        }
        document.getElementById('messageMediaUpload').addEventListener('change', async function() {
            const file = this.files[0];
            await importKeyPair(file);
            handleUpdateEvent();
        });
    }

    document.addEventListener('DOMContentLoaded', async () => {

        // Add event listener for 'update' event on messageFeedContainer div
        messageFeedContainer.addEventListener("updateNeeded", handleUpdateEvent);

        // Fetch messages initially
        // handleUpdateEvent();

        <?php session_start(); ?>
        handleUpdateEvent('<?= htmlspecialchars($_SESSION['reqUsername'] ?? '') ?>');

    });
</script>