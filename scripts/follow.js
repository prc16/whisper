// Function to display followees
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
            <div class="displayfolloweeButtons">
                    <button class="follow-btn btn" data-id="${followee.username}" data-type="unfollow">Unfollow</button>
            </div>
        </div>
    `;
        followeesFeedContainer.appendChild(followeeElement);
    });
}

// Function to handle follow requests
function follow(event) {
    const {
        target
    } = event;
    if (target.classList.contains('follow-btn')) {
        const type = target.dataset.type;
        const username = target.dataset.id;

        const formData = new FormData();
        formData.append('action', type);
        formData.append('username', username);

        fetch('/server/follow', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                // Successful follow request
                target.dataset.type = type == 'follow' ? 'unfollow' : 'follow';
                target.innerHTML = type === 'follow' ? 'Unfollow' : 'Follow';
            } else {
                // Parse JSON response
                return response.json().then(data => {
                    // Server returned an error, display the error message
                    alert(data.message);
                    console.log(data.message);
                });
            }
        })
        .catch(error => {
            console.error('There was a problem with your fetch operation:', error);
        });
    }
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
