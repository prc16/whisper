<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/php/userDetails.php'; ?>
<?php if ($loggedIn) : ?>
    <div id="followeesFeedContainer"></div>
    <script>
        function displayFollowees(followees) {
            const followeesFeedContainer = document.getElementById('followeesFeedContainer');
            if (!followeesFeedContainer) {
                console.error("followees container not found");
                return;
            }
            followeesFeedContainer.innerHTML = '';
            followees.forEach(followee => {
                const followeeElement = document.createElement('div');
                followeeElement.className = 'followee';
                followeeElement.innerHTML = `
        <div class="profile_link" onclick="handleUpdateEvent('${followee.username}')">
        <div class="displayFolloweeContainer">
            <div class="displayFolloweeProfile">
                <img src="${followee.profile_file_path}" class="profile-picture" alt="">
                <h2 class="profile-username">${followee.username}</h2>
            </div>
        </div>
        </div>
    `;
                followeesFeedContainer.appendChild(followeeElement);
            });
        }

        function fetchFollowees() {
            fetch('/server/following')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(followees => {
                    displayFollowees(followees);
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                });
        }
        document.addEventListener('DOMContentLoaded', () => {

            // Fetch posts initially
            fetchFollowees();

            // Fetch posts every 5 seconds
            // setInterval(handleUpdateEvent, 5000);
        });
    </script>
<?php endif; ?>