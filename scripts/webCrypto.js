async function generateKeyPair() {
    const keyPair = await window.crypto.subtle.generateKey({
        name: "ECDH",
        namedCurve: "P-256",
    },
        true,
        ["deriveKey", "deriveBits"]
    );

    const publicKeyJwk = await window.crypto.subtle.exportKey(
        "jwk",
        keyPair.publicKey
    );

    const privateKeyJwk = await window.crypto.subtle.exportKey(
        "jwk",
        keyPair.privateKey
    );

    return {
        publicKeyJwk,
        privateKeyJwk
    };
}

async function deriveEncryptionKey(publicKeyJwk, privateKeyJwk) {
    try {
        const publicKey = await window.crypto.subtle.importKey(
            "jwk",
            publicKeyJwk, {
            name: "ECDH",
            namedCurve: "P-256",
        },
            true,
            []
        );

        const privateKey = await window.crypto.subtle.importKey(
            "jwk",
            privateKeyJwk, {
            name: "ECDH",
            namedCurve: "P-256",
        },
            true,
            ["deriveKey", "deriveBits"]
        );

        return await window.crypto.subtle.deriveKey({
            name: "ECDH",
            public: publicKey
        },
            privateKey, {
            name: "AES-GCM",
            length: 256
        },
            true,
            ["encrypt", "decrypt"]
        );
    } catch (error) {
        console.error('Error deriving encryption key:', error);
        // Handle errors as needed
    }
}

async function encryptText(text, derivedKey) {
    try {
        const encodedText = new TextEncoder().encode(text);

        const initializationVector = window.crypto.getRandomValues(new Uint8Array(12));

        const encryptedData = await window.crypto.subtle.encrypt({
            name: "AES-GCM",
            iv: initializationVector
        },
            derivedKey,
            encodedText
        );

        // Convert encrypted data and initialization vector to base64
        const base64Data = btoa(String.fromCharCode.apply(null, new Uint8Array(encryptedData)));
        const base64IV = btoa(String.fromCharCode.apply(null, initializationVector));

        return {
            base64Data,
            base64IV
        };
    } catch (error) {
        console.error('Error encrypting text:', error);
        // Handle errors as needed
    }
}

async function decryptText(encryptedData, initializationVector, derivedKey) {
    try {
        const string = atob(encryptedData);
        const uintArray = new Uint8Array(
            [...string].map((char) => char.charCodeAt(0))
        );
        const algorithm = {
            name: "AES-GCM",
            iv: new Uint8Array(atob(initializationVector).split('').map(c => c.charCodeAt(0))),
        };
        const decryptedData = await window.crypto.subtle.decrypt(
            algorithm,
            derivedKey,
            uintArray
        );

        return new TextDecoder().decode(decryptedData);
    } catch (error) {
        console.error('Error decrypting text:', error);
        return `Error decrypting text: ${error}`;
        // Handle errors as needed
    }
}

async function sendPublicKey(publicKeyJwk) {
    try {
        const response = await fetch('/server/post/publicKeyJWK', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                publicKeyJwk
            })
        });

        if (!response.ok) {
            throw new Error('Failed to store public key');
        }

        const data = await response.json();
        const keyPairId = data.keyPairId;

        console.log('Public key sent successfully.');
        return keyPairId;
    } catch (error) {
        console.error('Error sending public key:', error);
    }
}

async function storeKeyPairInIndexedDB(keyPair, keyPairId) {
    try {
        // Generate key pair
        // const keyPair = await generateKeyPair();

        // Open a connection to IndexedDB
        const db = await idb.openDB('keyPairsDB', 1, {
            upgrade(db) {
                db.createObjectStore('keyPairs');
            },
        });

        // Store key pair in the database
        const tx = db.transaction('keyPairs', 'readwrite');
        const store = tx.objectStore('keyPairs');
        await store.put(keyPair, keyPairId);
        await tx.done;

        console.log('Key pair stored in IndexedDB.');
    } catch (error) {
        console.error('Error storing key pair in IndexedDB:', error);
    }
}

async function initKeys() {
    try {
        // Generate key pair
        const keyPair = await generateKeyPair();

        // Send public key to server
        const keyPairId = await sendPublicKey(keyPair.publicKeyJwk);

        // Store key pair in IndexedDB
        await storeKeyPairInIndexedDB(keyPair, keyPairId);

        // Generate JSON object containing key pair and key pair ID
        const keysData = {
            keyPairId,
            keyPair
        };

        // Convert JSON object to string
        const keysJson = JSON.stringify(keysData);

        // Create a Blob object containing the JSON string
        const blob = new Blob([keysJson], { type: 'application/json' });

        // Create a temporary URL for the Blob
        const url = URL.createObjectURL(blob);

        // Create a link element
        const link = document.createElement('a');
        link.href = url;
        link.download = 'keyPair_' + keyPairId + '.json'; // Specify the filename for the downloaded file
        link.click();

        // Cleanup: remove the temporary URL
        URL.revokeObjectURL(url);

        // Redirect to home page
        window.location.href = '/home';

    } catch (error) {
        console.error('Error initializing keys:', error);
    }
}
