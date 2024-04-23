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
