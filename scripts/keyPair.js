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

        console.log('Public key sent successfully.');
        return response.json().keyPairId;
    } catch (error) {
        console.error('Error sending public key:', error);
    }
}