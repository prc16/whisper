<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/whisper/topbar-middle/content.php'; ?>
<div id="followeesFeedContainer"></div>
<div id="postsFeedContainer"></div>
<script src="/scripts/posts.js"></script>
<script>
    // Function to handle the 'updateNeeded' event
    function handleUpdateEvent() {
        fetchResults('<?= htmlspecialchars($_GET['query'] ?? null) ?>');
    }

    document.addEventListener('DOMContentLoaded', () => {

        // Add event listener for 'update' event on displayPosts div
        postsFeedContainer.addEventListener("updateNeeded", handleUpdateEvent);
        
        // Fetch posts initially
        handleUpdateEvent();

        // Event listener for voting
        document.addEventListener('click', vote);

        // Fetch posts every 5 seconds
        // setInterval(handleUpdateEvent, 5000);
    });


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
        <div class="displayFolloweeContainer">
            <div class="displayFolloweeProfile">
                <a href="/u/${followee.username}" class="profile_link"><img src="${followee.profile_file_path}" class="profile-picture" alt=""></a>
                <a href="/u/${followee.username}" class="username_link"><h2 class="profile-username">${followee.username}</h2></a>
            </div>
        </div>
    `;
        followeesFeedContainer.appendChild(followeeElement);
    });
}

function fetchResults(query) {
    fetch('/server/search/'+query)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            displayFollowees(data.usernames);
            displayPosts(data.posts);
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });
}
</script>   