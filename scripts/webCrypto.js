async function generateKeyPair() {
    const keyPair = await crypto.subtle.generateKey(
        {
            name: "ECDH",
            namedCurve: "P-256" // you can choose other curves as well
        },
        true,
        ["deriveKey"]
    );

    const publicKey = await crypto.subtle.exportKey("spki", keyPair.publicKey);
    const privateKey = await crypto.subtle.exportKey("pkcs8", keyPair.privateKey);

    return { publicKey, privateKey };
}

async function deriveSharedSecret(theirPublicKey, myPrivateKey) {
    const publicKey = await crypto.subtle.importKey(
        "spki",
        theirPublicKey,
        {
            name: "ECDH",
            namedCurve: "P-256"
        },
        true,
        []
    );

    const privateKey = await crypto.subtle.importKey(
        "pkcs8",
        myPrivateKey,
        {
            name: "ECDH",
            namedCurve: "P-256"
        },
        true,
        ["deriveKey"]
    );

    const sharedKey = await crypto.subtle.deriveKey(
        {
            name: "ECDH",
            namedCurve: "P-256",
            public: publicKey
        },
        privateKey,
        {
            name: "AES-GCM",
            length: 256
        },
        true,
        ["encrypt", "decrypt"]
    );

    return sharedKey;
}
async function encryptMessage(message, sharedKey) {
    const iv = crypto.getRandomValues(new Uint8Array(12));
    const encodedMessage = new TextEncoder().encode(message);
    const ciphertext = await crypto.subtle.encrypt(
        {
            name: "AES-GCM",
            iv: iv
        },
        sharedKey,
        encodedMessage
    );
    return { ciphertext, iv };
}

async function decryptMessage(encryptedMessage, sharedKey, iv) {
    const decryptedMessage = await crypto.subtle.decrypt(
        {
            name: "AES-GCM",
            iv: iv
        },
        sharedKey,
        encryptedMessage
    );
    return new TextDecoder().decode(decryptedMessage);
}

async function exportKey(sharedKey) {
    const exportedKey = await crypto.subtle.exportKey("raw", sharedKey);
    return exportedKey;
}

function storeKeyInLocalStorage(key) {
    localStorage.setItem("sharedKey", arrayBufferToBase64(key));
}

function arrayBufferToBase64(buffer) {
    let binary = "";
    const bytes = new Uint8Array(buffer);
    const len = bytes.byteLength;
    for (let i = 0; i < len; i++) {
        binary += String.fromCharCode(bytes[i]);
    }
    return window.btoa(binary);
}

async function getKeyFromLocalStorage() {
    const storedKey = localStorage.getItem("sharedKey");
    if (storedKey) {
        const sharedKey = await crypto.subtle.importKey(
            "raw",
            base64ToArrayBuffer(storedKey),
            {
                name: "AES-GCM",
                length: 256
            },
            true,
            ["encrypt", "decrypt"]
        );
        return sharedKey;
    } else {
        return null;
    }
}

function base64ToArrayBuffer(base64) {
    const binaryString = window.atob(base64);
    const len = binaryString.length;
    const bytes = new Uint8Array(len);
    for (let i = 0; i < len; i++) {
        bytes[i] = binaryString.charCodeAt(i);
    }
    return bytes.buffer;
}


// Example usage
async function example() {
    // Alice generates a key pair
    const aliceKeyPair = await generateKeyPair();
  
    // Bob generates a key pair
    const bobKeyPair = await generateKeyPair();
  
    // Alice derives a shared key with Bob's public key and her private key
    const aliceSharedKey = await deriveSharedSecret(bobKeyPair.publicKey, aliceKeyPair.privateKey);
  
    // Bob derives a shared key with Alice's public key and his private key
    const bobSharedKey = await deriveSharedSecret(aliceKeyPair.publicKey, bobKeyPair.privateKey);
  
    // Alice exports her shared key and stores it in local storage
    const exportedAliceKey = await exportKey(aliceSharedKey);
    storeKeyInLocalStorage(exportedAliceKey);
  
    // Bob retrieves the shared key from local storage
    const importedAliceKey = await getKeyFromLocalStorage();
  
    // Bob encrypts a message using the shared key
    const message = "Hello, Alice!";
    const iv = crypto.getRandomValues(new Uint8Array(12));
    const encryptedMessage = await crypto.subtle.encrypt(
      {
        name: "AES-GCM",
        iv: iv
      },
      importedAliceKey,
      new TextEncoder().encode(message)
    );
  
    // Alice decrypts the message using the shared key
    const decryptedMessage = await crypto.subtle.decrypt(
      {
        name: "AES-GCM",
        iv: iv
      },
      importedAliceKey,
      encryptedMessage
    );
  
    console.log("Decrypted message:", new TextDecoder().decode(decryptedMessage));
  }
  
  // Run the example
  example();

// Storing and retrieving functions remain the same as provided in the previous response.


// Storing and retrieving functions remain the same as provided in the previous response.

