<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/whisper/topbar-middle/content.php'; ?>
<div id="signup-container">
    <form id="signup-form">
        <div id="signupFormErrorMessage" class="errorMessage"></div>
        <input type="text" id="signup_username" name="signup_username" placeholder="Username" autocomplete="username" required>
        <input type="password" id="signup_password" name="signup_password" placeholder="Password" autocomplete="new-password" required>
        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm Password" autocomplete="new-password" required>
        <input type="submit" value="Sign Up" class="btn">
    </form>
</div>
<script src="/scripts/webCrypto.js"></script>
<script>
    async function fetchUUID() {
        try {
            const response = await fetch('/server/getUUID');
            if (!response.ok) {
                throw new Error('Failed to fetch UUID');
            }
            const data = await response.json();
            if (!data.UUID) {
                throw new Error('UUID not found in response');
            }
            return data.UUID;
        } catch (error) {
            handleFetchError('Error fetching UUID', error);
            return null;
        }
    }

    async function storeKeyPairInIndexedDB(keyPairId, keyPair) {
        try {
            const db = await idb.openDB('whisperDB', 1, { upgrade(db) { db.createObjectStore('keyPairs'); } });
            const tx = db.transaction('keyPairs', 'readwrite');
            await tx.store.put(keyPair, keyPairId);
            await tx.done;
            console.log('Key pair stored in IndexedDB.');
            return true;
        } catch (error) {
            handleIndexedDBError('Error storing key pair in IndexedDB', error);
            return false;
        }
    }

    async function postSignUpForm(username, password, keyPairId, publicKeyJwk) {
        try {
            const formData = new FormData();
            formData.append("signup_username", username);
            formData.append("signup_password", password);
            formData.append("keyPairId", keyPairId);
            formData.append("publicKeyJwk", JSON.stringify({publicKeyJwk}));
            const response = await fetch('/server/signup', { method: 'POST', body: formData });
            if (response.ok) {
                window.location.href = '/home';
            } else {
                const data = await response.json();
                errorMessageContainer.innerText = data.message;
                console.log(data.message);
            }
        } catch (error) {
            handleFetchError('There was a problem with your fetch operation', error);
        }
    }

    async function verifyAndSignUp(username, password) {
        try {
            const formData = new FormData();
            formData.append("signup_username", username);
            formData.append("signup_password", password);
            const response = await fetch('/server/signup_verify', { method: 'POST', body: formData });
            if (!response.ok) {
                const data = await response.json();
                errorMessageContainer.innerText = data.message;
                console.log(data.message);
                return false;
            }
            return true;
        } catch (error) {
            handleFetchError('There was a problem with your fetch operation', error);
            return false;
        }
    }

    function handleFetchError(message, error) {
        errorMessageContainer.innerText = message;
        console.error(message, error);
    }

    function handleIndexedDBError(message, error) {
        errorMessageContainer.innerText = message;
        console.error(message, error);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const errorMessageContainer = document.getElementById('signupFormErrorMessage');
        document.getElementById('signup-form').addEventListener('submit', async function(event) {
            event.preventDefault();
            const username = document.getElementById('signup_username').value;
            const password = document.getElementById('signup_password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            if (password !== confirmPassword) {
                errorMessageContainer.innerText = 'Passwords do not match.';
                return;
            }
            if (await verifyAndSignUp(username, password)) {
                const keyPairId = await fetchUUID();
                const keyPair = await generateKeyPair();
                if (keyPairId && keyPair && await storeKeyPairInIndexedDB(keyPairId, keyPair)) {
                    postSignUpForm(username, password, keyPairId, keyPair.publicKeyJwk);
                }
            }
        });
    });
</script>
