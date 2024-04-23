<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/whisper/topbar-middle/content.php'; ?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/php/userDetails.php'; ?>
<div id="settingsContainer">
    <div id="profile-container-large">
        <img id="profile-container-large-picture" src="<?= htmlspecialchars($profilePicture) ?>">
        <div id="profile-container-large-username">
            <?= htmlspecialchars($username) ?>
        </div>
        <div>
            keyPairId: <?= htmlspecialchars($keyPairId) ?>
        </div>
    </div>

    <div id="settingsButtons" class="profileButtonsContainer">
        <button id="keyPairExportButton" class="btn">Export keys</button>
        <button id="keyPairImportButton" class="btn">Import keys</button>
    </div>

    <div id="settingsErrorMessage" class="errorMessage"></div>

    <div id="exportKeysContainer" class="hidden">
        <h2>
            Download your key pair data
        </h2>
        <button id="keyPairDownloadButton" class="btn btn2">
            <i class="fas fa-download"></i>
            <div id="filenameContainer"><?= htmlspecialchars($username) ?>_keyPairData_<?= htmlspecialchars($keyPairId) ?>.json</div>
        </button>
        <p>
            These keys are used to encrypt and decrypt your message.
            <br>These keys are not stored on the server.
            <br>If you clear your browser data or switch browsers, you'll need to import these keys to access your messages again.
        </p>
    </div>

    <div id="importKeysContainer" class="hidden">
        <p>Select your keyPairData JSON file to import it to your browser.</p>

        <label for="importKeyPairData" class="btn btn2" title="Import Keys"><i class="far fa-file-import"></i></label>
        <input type="file" id="importKeyPairData" accept=".json">

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const errorMessageContainer = document.getElementById('settingsErrorMessage');
        const exportKeysContainer = document.getElementById('exportKeysContainer');
        const keyPairDownloadButton = document.getElementById('keyPairDownloadButton');
        const filenameContainer = document.getElementById('filenameContainer');
        const keyPairExportButton = document.getElementById('keyPairExportButton');
        const keyPairImportButton = document.getElementById('keyPairImportButton');
        const importKeysContainer = document.getElementById('importKeysContainer');

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
                    return null;
                }
                return keyPair;
            } catch (error) {
                errorMessageContainer.innerText = 'Error retrieving key pair from IndexedDB';
                console.error('Error retrieving key pair from IndexedDB:', error);
                return null;
            }
        }

        async function storeKeyPairInIndexedDB(keyPairId, keyPair) {
            try {
                const db = await idb.openDB('whisperDB', 1, {
                    upgrade(db) {
                        db.createObjectStore('keyPairs');
                    }
                });
                const tx = db.transaction('keyPairs', 'readwrite');
                await tx.store.put(keyPair, keyPairId);
                await tx.done;
                console.log('Key pair stored in IndexedDB.');
                return true;
            } catch (error) {
                errorMessageContainer.innerText = 'Error storing key pair in IndexedDB';
                console.error('Error storing key pair in IndexedDB: ', error);
                return false;
            }
        }

        document.getElementById('importKeyPairData').addEventListener('change', async function() {
            const file = this.files[0];
            await importKeyPair(file);
            this.value = null;
        });

        async function importKeyPair(file) {
            if (!file) {
                alert('Please select a file.');
                return false;
            }

            try {
                const keyPairData = await readFileAsJSON(file);
                console.log('Imported key pair:', keyPairData);
                if (await storeKeyPairInIndexedDB(keyPairData.keyPairId, keyPairData.keyPair)) {
                    const keyPair = await retrieveKeyPairFromIndexedDB();
                    if (keyPair) {
                        alert('Key pair imported successfully.');
                        importKeysContainer.classList.add('hidden');
                        errorMessageContainer.innerText = '';
                        return true;
                    } else {
                        alert('This is not your keyPairData file. Please select correct file.');
                        return false;
                    }
                }
            } catch (error) {
                console.error('Error importing key pair:', error);
                return false
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

        function downloadJsonFile(data, filename) {
            const blob = new Blob([data], {
                type: 'application/json'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            setTimeout(() => {
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            }, 0);
        }

        keyPairDownloadButton.addEventListener('click', async function() {
            const keyPair = await retrieveKeyPairFromIndexedDB();
            if (keyPair) {
                const data = {
                    keyPairId: '<?= htmlspecialchars($keyPairId) ?>',
                    keyPair: keyPair
                };
                const jsonData = JSON.stringify(data);
                const filename = '<?= htmlspecialchars($username) ?>_keyPairData_<?= htmlspecialchars($keyPairId) ?>.json';
                downloadJsonFile(jsonData, filename);
            }
        });

        keyPairExportButton.addEventListener('click', async function() {
            errorMessageContainer.innerText = '';
            importKeysContainer.classList.add('hidden');
            const keyPair = await retrieveKeyPairFromIndexedDB();
            if (keyPair) {
                exportKeysContainer.classList.remove('hidden');
            } else {
                errorMessageContainer.innerText = 'Key pair not found in IndexedDB.';
            }
        });

        keyPairImportButton.addEventListener('click', async function() {
            errorMessageContainer.innerText = '';
            const keyPair = await retrieveKeyPairFromIndexedDB();
            if (keyPair) {
                errorMessageContainer.innerText = 'Your key pair already exists in IndexedDB';
            }
            exportKeysContainer.classList.add('hidden');
            importKeysContainer.classList.remove('hidden');
        });

    });
</script>