<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/whisper/topbar-middle/content.php'; ?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/php/userDetails.php'; ?>
<div id="settingsContainer">
    <input type="file" id="fileInput" accept=".json">
    <button onclick="importKeyPair()">Import Key Pair</button>
</div>
<script src="/scripts/webCrypto.js"></script>
<script>
    async function importKeyPair() {
            const fileInput = document.getElementById('fileInput');
            const file = fileInput.files[0];

            if (!file) {
                alert('Please select a file.');
                return;
            }

            try {
                const keyPairData = await readFileAsJSON(file);
                console.log('Imported key pair:', keyPairData);
                storeKeyPairInIndexedDB(keyPairData.keyPair, keyPairData.keyPairId);

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
</script>